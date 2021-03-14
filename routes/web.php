<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'IndexController@exibirVideos')->name('exibir_videos');
Route::get('/playlists', 'IndexController@exibirPlaylists')->name('exibir_playlists');

Route::prefix('/ajax')->group(function () {
    Route::post('/gerar_playlist', 'AjaxController@gerarPlaylist')->name('gerar_playlist');
    Route::post('/refazer_playlist', 'AjaxController@refazerPlaylist')->name('refazer_playlist');
    Route::post('/dados_video_modal', 'AjaxController@buscarDadosVideoModal')->name('dados_video_modal');
    Route::post('/salvar_video', 'AjaxController@salvarVideo')->name('salvar_video');
    Route::post('/play', 'AjaxController@play')->name('play');
    Route::post('/favorito', 'AjaxController@favorito')->name('favorito');
    Route::post('/hot', 'AjaxController@hot')->name('hot');
    Route::post('/filtrar_videos', 'AjaxController@filtrarVideos')->name('filtrar_videos');
    Route::get('/excluir_video/{video}', 'AjaxController@excluirVideo')->name('excluir_video');
    Route::get('/girar_video/{video}', 'AjaxController@girarVideo')->name('girar_video');
    Route::get('/traduzir/{texto}', 'AjaxController@traduzir')->name('traduzir');
});

Route::prefix('/storage')->group(function () {
    Route::get('/atualizar_videos/{novos?}', 'StorageController@atualizarVideosStorage')->name('atualizar_videos_storage');
    Route::get('/atualizar_video/{id}', 'StorageController@atualizarVideoStorage')->name('atualizar_video_storage');
});

Route::any('exportar', 'ExportarController@exportar')->name('exportar');

Route::get('youtube/{id}', function ($id) {    function get($id, $pagina='') {
        $playlistItems = Youtube::getPlaylistItemsByPlaylistId($id, $pagina);
        foreach ($playlistItems['results'] as $v) {
            echo "https://www.youtube.com/watch?v=".$v->snippet->resourceId->videoId.PHP_EOL;
        }
        if (!empty($playlistItems['info']['nextPageToken'])) {
            get($id, $playlistItems['info']['nextPageToken']);
        }
    }
    get($id);
});

use Stichoza\GoogleTranslate\GoogleTranslate;
Route::get('teste', function () {    
    $tr = new GoogleTranslate('en', 'ko');
    echo $tr->translate('우기');
    return null;
});