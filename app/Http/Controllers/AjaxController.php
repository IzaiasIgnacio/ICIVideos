<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Video;
use App\Models\VideoArtista;
use App\Models\VideoMusica;
use App\Models\VideoTag;
use App\Models\VideoAudio;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\StorageController;
use Stichoza\GoogleTranslate\GoogleTranslate;

class AjaxController extends Controller {

    public function gerarPlaylist(Request $request) {
        return Playlist::gerarPlaylist($request);
    }
    
    public function refazerPlaylist(Request $request) {
        $playlist = Playlist::find($request['id']);
        return $playlist->refazerPlaylist($playlist);
    }

    public function buscarDadosVideoModal(Request $request) {
        $video = new Video();
        return $video->buscarDadosModal($request['id']);
    }

    public function salvarVideo(Request $request) {
        $video = Video::find($request['id_video']);
        return $video->salvar($request->all());
    }

    public function play(Request $request) {
        Storage::disk('videos')->put('play.m3u', Storage::disk('videos')->url(Video::find($request['id'])->buscarCaminhoCompleto()));
    }

    public function favorito(Request $request) {
        $video = Video::find($request['id']);
        $video->favorito = !$video->favorito;
        $video->save();
    }

    public function hot(Request $request) {
        $id_hot = Tag::where('nome', 'Hot')->first()->id;
        $video_tag = VideoTag::where('id_video', $request['id'])->where('id_tag', $id_hot);
        if ($video_tag->exists()) {
            $video_tag->delete();
        }
        else {
            $vt = new VideoTag();
            $vt->id_tag = $id_hot;
            $vt->id_video = $request['id'];
            $vt->save();
        }
    }

    public function filtrarVideos(Request $request) {
        // para testar return $request->all();
        $videos = Video::buscarVideosIndex($request->all());
        $html = view('tabela_videos', [
            'videos' => $videos
        ])->render();
        $sem_musica = Video::videosSemMusica($videos->pluck('id'));

        return ['html' => $html, 'sem_musica' => $sem_musica, 'total_videos' => $videos->count()];
    }

    public function excluirVideo($args) {
        try {
            DB::connection('icivideos')->beginTransaction();
            VideoArtista::where('id_video', $args)->delete();
            VideoMusica::where('id_video', $args)->delete();
            VideoTag::where('id_video', $args)->delete();
            VideoAudio::where('id_video', $args)->delete();

            for ($i=1;$i<=8;$i++) {
                Storage::disk('public')->delete('capturas/'.$args.'_'.$i.'.png');
            }

            $video = Video::find($args);
            Storage::disk('videos')->delete($video->buscarCaminhoCompleto());
            $video->delete();

            DB::connection('icivideos')->commit();
            return 'ok';
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
    
    public function girarVideo($args) {
        $retorno = null;
        try {
            $video = Video::find($args);

            $raiz = 'K:/Vídeos/music/';
            exec(escapeshellcmd("powershell -noprofile -command ffmpeg -i '".$raiz.$video->buscarCaminhoCompleto()."' -c copy -metadata:s:v:0 rotate=-270 '".$raiz.$video->caminho."/output.mp4'"), $retorno, $resultado);

            if ($resultado == 0) {
                Storage::disk('videos')->delete($video->buscarCaminhoCompleto());
                Storage::disk('videos')->move($video->caminho."/output.mp4", $video->caminho."/".$video->titulo.".mp4");
                
                $storage = new StorageController();
                $storage->salvarVideo($video->caminho."/".$video->titulo.".mp4", null, null, null, $video);
                return 'ok';
            }

            return 'falha';
        }
        catch (\Exception $ex) {
            $retorno = $ex->getMessage().' '.$ex->getFile().' '.$ex->getLine();
        }
        catch (\Error $ex) {
            $retorno = $ex->getMessage().' '.$ex->getFile().' '.$ex->getLine();
        }

        return $retorno;
    }

    public function traduzir($args) {
        $tr = new GoogleTranslate('en', 'ko');
        return $tr->translate($args);
    }

    public function filtrarRelatorio(Request $request) {
        $db = \Config::get('app.db');
        $connection = DB::connection($db);

        $tags = Tag::buscarTagsArtista($request->artista);

        $musicas = Video::select('musica.titulo', $connection->raw('count(distinct video.id) as total'))
                    // $connection->raw('sum(video.id_tipo = 1) as lives'), $connection->raw('sum(video.id_tipo = 2) as mvs'))
                        ->join('video_artista', 'video_artista.id_video', 'video.id')
                        ->join('video_musica', 'video_musica.id_video', 'video.id')
                        ->join('musica', 'musica.id', 'id_musica')
                        ->leftJoin('video_tag', 'video_tag.id_video', 'video.id')
                            ->where('id_artista', $request->artista)
                                ->groupBy('id_musica')
                                    ->orderByDesc('total');

        if (!empty($tags)) {
            foreach ($tags as $tag) {
                $musicas->addSelect($connection->raw('sum(video_tag.id_tag = '.$tag->id.') as "'.$tag->nome.'"'));
            }
        }

        $musicas->addSelect($connection->raw('sum(video_tag.id_tag is null) as sem_tag'));
        
        $html = view('tabela_relatorio', [
            'musicas' => $musicas->get(),
            'tags' => $tags
        ])->render();

        return ['html' => $html];
    }

}