<?php

namespace App\Observers;

use App\Models\VideoArtista;
use App\Models\Playlist;

class VideoArtistaObserver {

    public function created(VideoArtista $video_artista) {
        $playlists = Playlist::buscarPorValor(['campo' => 'artistas', 'valor' => $video_artista->id_artista]);
        foreach ($playlists as $playlist) {
            $playlist->atualizar = 1;
            $playlist->save();
        }
    }

    public function deleting(VideoArtista $video_artista) {
        $playlists = Playlist::buscarPorValor(['campo' => 'artistas', 'valor' => $video_artista->id_artista]);
        foreach ($playlists as $playlist) {
            $playlist->atualizar = 1;
            $playlist->save();
        }
    }

}