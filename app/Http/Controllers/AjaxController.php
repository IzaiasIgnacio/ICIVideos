<?php

namespace App\Http\Controllers;

use Config;
use App\Models\Playlist;
use App\Models\Video;
use App\Models\Categoria;
use App\Models\Artista;
use App\Models\Tag;
use App\Models\Musica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AjaxController extends Controller {

    public function gerarPlaylist(Request $request) {
        return Playlist::gerarPlaylist($request);
    }

    public function buscarDadosVideoModal(Request $request) {
        $video = new Video();
        return $video->buscarDadosModal($request['id']);
    }

    public function salvarVideo(Request $request) {
        $video = Video::find($request['id_video']);
        return $video->salvar($request->all());
    }

    public function play(Request $request) {
        Storage::disk('videos')->put('play.m3u', Storage::disk('videos')->url(Video::find($request['id'])->buscarCaminhoCompleto()));
    }

    public function favorito(Request $request) {
        $video = Video::find($request['id']);
        $video->favorito = !$video->favorito;
        $video->save();
    }

    public function filtrarVideos(Request $request) {
        // return Video::buscarVideosIndex($request->all());
        $html = view('tabela_videos', [
            'videos' => Video::buscarVideosIndex($request->all())
        ])->render();

        return ['html' => $html];
    }
    
}