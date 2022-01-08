<?php

namespace App\Observers;

use App\Models\VideoMusica;
use App\Models\Playlist;

class VideoMusicaObserver {

    public function created(VideoMusica $video_musica) {
        $playlists = Playlist::buscarPorValor(['campo' => 'musicas', 'valor' => $video_musica->id_musica]);
        foreach ($playlists as $playlist) {
            $playlist->atualizar = 1;
            $playlist->save();
        }
    }

    public function deleting(VideoMusica $video_musica) {
        $playlists = Playlist::buscarPorValor(['campo' => 'musicas', 'valor' => $video_musica->id_musica]);
        foreach ($playlists as $playlist) {
            $playlist->atualizar = 1;
            $playlist->save();
        }
    }

}