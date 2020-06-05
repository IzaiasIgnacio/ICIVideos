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
            'pagina' => 'videos'
        ]);
    }

    public function exibirPlaylists() {
        return view('index', [
            'categorias' => Categoria::get()->pluck('nome'),
            'artistas' => Artista::get()->pluck('nome'),
            'tags' => Tag::get()->pluck('nome'),
            'musicas' => Musica::get()->pluck('titulo'),
            'playlists' => Playlist::buscarPlaylists(),
            'pagina' => 'playlists'
        ]);
    }

}