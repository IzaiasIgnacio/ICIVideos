<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Video;
use Illuminate\Http\Request;

class AjaxController extends Controller {

    public function gerarPlaylist(Request $request) {
        return Playlist::gerarPlaylist($request);
    }

    public function buscarDadosVideoModal(Request $request) {
        $video = new Video();
        return $video->buscarDadosModal($request['id']);
    }

}