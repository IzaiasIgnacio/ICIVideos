<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Categoria;
use App\Models\Artista;
use App\Models\Tag;
use App\Models\Musica;
use App\Models\Playlist;

class IndexController extends Controller {

    public function exibirVideos() {
        return view('index', [
            'videos' => Video::buscarVideosIndex(),
            'musicas' => Musica::get()->pluck('titulo', 'id'),
            'tags' => Tag::get()->pluck('nome', 'id'),
            'pagina' => 'videos'
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
            'pagina' => 'playlists'
        ]);
    }

}