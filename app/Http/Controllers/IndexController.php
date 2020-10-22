<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Categoria;
use App\Models\Artista;
use App\Models\Tag;
use App\Models\Musica;
use App\Models\Playlist;
use Config;

class IndexController extends Controller {

    public function exibirVideos() {
        return view('index', [
            'videos' => Video::buscarVideosIndex(),
            'titulos' => Video::get()->pluck('titulo', 'titulo'),
            'musicas' => Musica::get()->pluck('titulo', 'titulo'),
            'tags' => (object) array_merge(['sem_tag' => 'Sem Tag'], Tag::get()->pluck('nome', 'nome')->toArray()),
            'artistas' => Artista::orderBy('nome')->get()->pluck('nome', 'nome'),
            'pagina' => 'videos',
            'db' => Config::get('app.db'),
            'sem_musica' => Video::videosSemMusica(Video::whereIn('id_tipo', [1,2])->pluck('id')),
            'total_videos' => Video::whereIn('id_tipo', [1,2])->count()
        ]);
    }

    public function exibirPlaylists() {
        $playlist = new Playlist();

        return view('index', [
            'categorias' => Categoria::get()->pluck('nome', 'id'),
            'artistas' => Artista::get()->pluck('nome', 'id'),
            'tags' => Tag::get()->pluck('nome', 'id'),
            'musicas' => Musica::get()->pluck('titulo', 'id'),
            'playlists' => $playlist->buscarPlaylists(),
            'pagina' => 'playlists',
            'db' => Config::get('app.db')
        ]);
    }

}