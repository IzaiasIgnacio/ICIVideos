<?php

namespace App\Observers;

use App\Models\Video;
use App\Models\Playlist;

class VideoObserver {

    public function updated(Video $video) {
        $playlists = [];

        if ($video->wasChanged('favorito')) {
            $playlists = Playlist::buscarPorValor(['campo' => 'favoritos', 'valor' => 'true']);
        }
        
        if (!empty($playlists)) {
            foreach ($playlists as $playlist) {
                $playlist->atualizar = 1;
                $playlist->save();
            }
        }

        $playlists = Playlist::buscarPorValor(['campo' => 'categorias', 'valor' => $video->id_categoria]);
        
        if (!empty($playlists)) {
            foreach ($playlists as $playlist) {
                $playlist->atualizar = 1;
                $playlist->save();
            }
        }
    }

}