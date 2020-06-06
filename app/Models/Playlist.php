<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Playlist extends Model {

	protected $table = 'playlist';
	protected $connection = 'icivideos';
	public $timestamps = false;
    protected $guarded = [];

    public $artistas;

    public static function gerarPlaylist($filtros) {
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
                        ->whereIn('video_musica.id_musica', $filtros['artistas']);
        }

        if (isset($filtros['tipos']) && count($filtros['tipos']) > 0) {
            $busca->whereIn('video.id_tipo', $filtros['tipos']);
        }
        
        if ($filtros['favoritos'] != 'false') {
            $busca->where('video.favorito', 1);
        }

        $videos = $busca->get();

        $caminhos = array();
        foreach ($videos as $video) {
            $caminhos[] = Storage::disk('videos')->url($video->buscarCaminhoCompleto());
        }

        shuffle($caminhos);
        Storage::disk('videos')->put($filtros['nome'].'.m3u', implode("\r\n", $caminhos));

        $playlist = Playlist::firstOrCreate([
            'nome' => $filtros['nome']
        ]);
        unset($filtros['nome']);
        $playlist->filtros = \json_encode($filtros->all());
        $playlist->save();

        return 'ok';
    }

    public static function buscarPlaylists() {
        $playlists = Playlist::get();
        
        foreach ($playlists as $playlist) {
            $filtros = json_decode($playlist['filtros'], true);
            $playlist->artistas = @implode(", ", $filtros['artistas']);
        }
        
        return $playlists;
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