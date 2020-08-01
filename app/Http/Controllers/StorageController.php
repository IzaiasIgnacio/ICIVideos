<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use \Intervention\Image\Facades\Image;
use App\Models;
use FFMpeg;
use BigFileTools\BigFileTools;

class StorageController extends Controller {

    private $probe;
    private $novos;
    private $contador_novos;

    public function __construct() {
        ini_set('max_execution_time', 0);
        $this->novos = false;
        $this->getProbe();
    }

    private function getProbe() {
        if (empty($probe)) {
            $this->probe = FFMpeg\FFProbe::create([
                'ffmpeg.binaries'  => 'I:/tools/ffmpeg/bin/ffmpeg.exe',
                'ffprobe.binaries' => 'I:/tools/ffmpeg/bin/ffprobe.exe'
            ]);
        }
    }

    public function atualizarVideoStorage(Request $request) {
        ini_set('max_execution_time', 0);
        $video = Models\Video::find($request['id']);
        $this->salvarVideo($video->buscarCaminhoCompleto(), null, Models\VideoArtista::where('id_video', $video->id)->first()->id_artista, null, $video);
    }

    public function atualizarVideosStorage(Request $request) {
        $this->novos = !empty($request['novos']);
    
        $categorias = Models\Categoria::get();

        try {
            $this->contador_novos = 0;
            DB::connection('icivideos')->beginTransaction();

            foreach ($categorias as $categoria) {
                $pastas_artista = Storage::disk('videos')->directories($categoria->pasta);
                
                foreach ($pastas_artista as $pasta) {
                    $artista = Models\Artista::buscarCriarArtistaPorNomePasta(str_replace($categoria->pasta.'/','',$pasta));
                    $artista->save();

                    $this->salvarVideosArtistaTipo($pasta, $categoria->id, $artista->id, 'Misc');
                    $this->salvarVideosArtistaTipo($pasta, $categoria->id, $artista->id, 'Live');
                    $this->salvarVideosArtistaTipo($pasta, $categoria->id, $artista->id, 'Mv');
                }
            }

            DB::connection('icivideos')->commit();
            return $this->contador_novos;
        }
        catch (\Exception $ex) {
            DB::connection('icivideos')->rollback();
            return $ex->getMessage().' '.$ex->getFile().' '.$ex->getLine();
        }
        catch (\Error $ex) {
            DB::connection('icivideos')->rollback();
            return $ex->getMessage().' '.$ex->getFile().' '.$ex->getLine();
        }
    }

    private function salvarVideosArtistaTipo($pasta, $id_categoria, $id_artista, $nome_tipo) {
        $tipo = Models\Tipo::where('nome', $nome_tipo)->first();
        $arquivos = Storage::disk('videos')->files($pasta.'/'.$tipo->pasta);
        foreach ($arquivos as $arquivo) {
            $this->salvarVideo($arquivo, $id_categoria, $id_artista, $tipo->id);
        }
    }

    private function salvarVideo($arquivo, $id_categoria, $id_artista, $id_tipo, $video = null) {
        $info = pathinfo($arquivo);

        if ($this->novos) {
            if (Models\Video::videoExiste($info['filename'], $id_categoria, $id_artista, $id_tipo)) {
                return;
            }
        }
        
        $caminho = Storage::disk('videos')->url($arquivo);
        $video_info = $this->probe->streams($caminho)->videos()->first();
        if ($video_info == null) {
            return;
        }
        $audio_info = $this->probe->streams($caminho)->audios();

        if (empty($video)) { 
            $video = Models\Video::buscarOucriarVideo($info['filename'], $id_artista, $id_categoria, $id_tipo);
        }

        $video->caminho = $info['dirname'];
        $video->extensao = $info['extension'];
        $video->duracao = $this->probe->format($caminho)->get('duration');
        $video->resolucao = $video_info->get('width').'X'.$video_info->get('height');
        $video->formato_video = $video_info->get('codec_long_name');
        $video->fps = \number_format(eval('return @'.$video_info->get('avg_frame_rate').';'), 2);
        $video->tamanho = (string) BigFileTools::createDefault()->getFile($caminho)->getSize() / 1024;
        $video->data_arquivo = date('Y-m-d', filemtime($caminho));
        $video->save();

        Models\VideoArtista::firstOrCreate([
            'id_video' => $video->id,
            'id_artista' => $id_artista
        ]);

        foreach ($audio_info as $audio) {
            Models\VideoAudio::firstOrCreate([
                'id_video' => $video->id,
                'canais' => $audio->get('channels'),
                'formato' => $audio->get('codec_long_name')
            ]);
        }

        $this->gerarCapturas($video, $video->duracao);
        $this->contador_novos++;
    }

    public function gerarCapturas($video, $duracao = null) {
        Log::channel('videos')->debug($video->id.' gerar');
        try {
            if (empty($duracao)) {
                $duracao = $this->probe->format(Storage::disk('videos')->url($video->buscarCaminhoCompleto()))->get('duration');
            }

            $segundos = $duracao / 9;

            $frames = [
                $segundos * 1,
                $segundos * 2,
                $segundos * 3,
                $segundos * 4,
                $segundos * 5,
                $segundos * 6,
                $segundos * 7,
                $segundos * 8
            ];

            $media = FFMpeg::fromDisk('videos')->open($video->buscarCaminhoCompleto());
            $media->each($frames, function ($ffmpeg, $segundos, $chave) use ($video) {
                $ffmpeg->getFrameFromSeconds($segundos)->export()->toDisk('public')->save('capturas/'.$video->id.'_'.($chave+1).'.png');
            });

            $this->redimensionarCapturas($video);
        }
        catch (\Exception $ex) {
            Log::channel('videos')->debug($video->id.' erro '.$ex->getMessage().' '.$ex->getFile().' '.$ex->getLine());
            return $ex->getMessage().' '.$ex->getFile().' '.$ex->getLine();
        }
        catch (\Error $ex) {
            Log::channel('videos')->debug($video->id.' erro '.$ex->getMessage().' '.$ex->getFile().' '.$ex->getLine());
            return $ex->getMessage().' '.$ex->getFile().' '.$ex->getLine();
        }
    }

    public function redimensionarCapturas($video) {
        Log::channel('videos')->debug($video->id.' redimensionar');
        try {
            for ($i=1;$i<=8;$i++) {
                $captura = Image::make(Storage::disk('public')->url('capturas/'.$video->id.'_'.$i.'.png'));
            
                $altura_original = $captura->height();
                $altura_nova = 360;

                if ($altura_original <= $altura_nova) {
                    Log::channel('videos')->debug($video->id.' altura 360');
                    return;
                }

                $largura_original = $captura->width();
                $proporcao = ($largura_original/$altura_original);
                $largura_nova = $altura_nova * $proporcao;
                
                $captura->resize($largura_nova, $altura_nova);

                Storage::disk('public')->put('capturas/'.$video->id.'_'.$i.'.png', $captura->encode());
            }
            Log::channel('videos')->debug($video->id.' ok');
        }
        catch (\Exception $ex) {
            Log::channel('videos')->debug($video->id.' erro '.$ex->getMessage().' '.$ex->getFile().' '.$ex->getLine());
            return $ex->getMessage().' '.$ex->getFile().' '.$ex->getLine();
        }
        catch (\Error $ex) {
            Log::channel('videos')->debug($video->id.' '.$ex->getMessage().' '.$ex->getFile().' '.$ex->getLine());
            return $ex->getMessage().' '.$ex->getFile().' '.$ex->getLine();
        }
    }

}