<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class Playlist extends Model {

	protected $table = 'playlist';
	protected $connection = 'icivideos';
	public $timestamps = false;
    protected $guarded = [];

    public $artistas;

    public static function gerarPlaylist($filtros, $salvar_banco=true) {
        $busca = Video::query();

        if (!empty($filtros['categorias'])) {
            $busca->whereIn('id_categoria', $filtros['categorias']);
        }

        if (!empty($filtros['artistas'])) {
            $busca->join('video_artista', 'video_artista.id_video', 'video.id')
                        ->whereIn('video_artista.id_artista', $filtros['artistas']);
        }
        
        if (!empty($filtros['tags'])) {
            $busca->join('video_tag', 'video_tag.id_video', 'video.id')
                        ->whereIn('video_tag.id_tag', $filtros['tags']);
        }
        
        if (!empty($filtros['musicas'])) {
            $busca->join('video_musica', 'video_musica.id_video', 'video.id')
                        ->whereIn('video_musica.id_musica', $filtros['musicas']);
        }

        if (isset($filtros['tipos']) && count($filtros['tipos']) > 0) {
            $busca->whereIn('video.id_tipo', $filtros['tipos']);
        }
        
        if (isset($filtros['favoritos']) && $filtros['favoritos'] != 'false') {
            $busca->where('video.favorito', 1);
        }

        if (!empty($filtros['resolucao'])) {
            $busca->where(DB::connection('icivideos')->raw("substr(resolucao, locate('X', resolucao)+1)"), '>=',(int) $filtros['resolucao']);
        }

        $videos = $busca->get();

        $caminhos = array();
        foreach ($videos as $video) {
            $caminhos[] = Storage::disk('videos')->url($video->buscarCaminhoCompleto());
        }

        shuffle($caminhos);
        Storage::disk('videos')->put($filtros['nome'].'.m3u', implode("\r\n", $caminhos));

        if ($salvar_banco) {
            $playlist = Playlist::firstOrCreate([
                'nome' => $filtros['nome']
            ]);

            unset($filtros['nome']);
            $playlist->filtros = \json_encode($filtros->all());
            $playlist->save();
        }

        return 'ok';
    }

    public function refazerPlaylist($playlist) {
        $filtros = array_merge(
            ['nome' => $playlist->nome],
            json_decode($playlist->filtros, true)
        );
        return $this->gerarPlaylist($filtros, false);
    }

    public function buscarPlaylists() {
        $playlists = Playlist::orderBy('nome')->get();
        
        foreach ($playlists as $playlist) {
            $filtros = json_decode($playlist['filtros'], true);

            $playlist = $this->buscarvaloresFiltros($filtros, $playlist);
        }
        
        return $playlists;
    }

    private function buscarvaloresFiltros($filtros, $playlist) {
        $playlist->favoritos = ($filtros['favoritos'] == "true") ? 'Sim' : 'Não';
        $playlist->resolucao = @$filtros['resolucao'];

        unset($filtros['favoritos']);
        unset($filtros['resolucao']);
        
        foreach ($filtros as $chave => $valores) {
            $classe = "\App\Models\\".ucfirst(substr($chave, 0, -1));
            $playlist->$chave = implode(", ", $classe::find($valores)->pluck('nome')->toArray());
        }
        
        return $playlist;
    }
    
}

// {
//     "datas": {
//         "intervalo": [{
//                 "de": "",
//                 "ate": ""
//             }],
//         "periodo": [{
//             "tipo": "dias|semanas|meses|anos",
//             "valor": 1
//         }]
//     },
//     "categorias": [
//         1,
//         2
//     ],
//     "artistas": [
//         1,
//         2
//     ],
//     "tipos": [
//         1,
//         2
//     ],
//     "tags": [
//         1,
//         2
//     ],
//     "favorito": "true|false|null",
//     "musicas": [
//         1,
//         2
//     ],
//     "resolucoes": [
//         "2160"
//     ],
//     "custom": [
//         1,
//         2
//     ]
// }