<?php

namespace App\Observers;

use App\Models\VideoTag;
use App\Models\Playlist;

class VideoTagObserver {

    public function created(VideoTag $video_tag) {
        $playlists = Playlist::buscarPorValor(['campo' => 'tags', 'valor' => $video_tag->id_tag]);
        foreach ($playlists as $playlist) {
            $playlist->atualizar = 1;
            $playlist->save();
        }
    }

    public function deleting(VideoTag $video_tag) {
        $playlists = Playlist::buscarPorValor(['campo' => 'tags', 'valor' => $video_tag->id_tag]);
        foreach ($playlists as $playlist) {
            $playlist->atualizar = 1;
            $playlist->save();
        }
    }

}