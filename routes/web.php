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
    Route::post('/dados_video_modal', 'AjaxController@buscarDadosVideoModal')->name('dados_video_modal');
});

Route::prefix('/storage')->group(function () {
    Route::get('/atualizar_videos/{novos?}', 'StorageController@atualizarVideosStorage')->name('atualizar_videos_storage');
    Route::get('/atualizar_video/{id}', 'StorageController@atualizarVideoStorage')->name('atualizar_video_storage');
});

Route::get('teste', function () {
    $img = \Intervention\Image\Facades\Image::make(Storage::disk('public')->url('capturas/1_1.png'))->resize(300, 200);

    $img->save('I:/xampp/htdocs/ICIVideos/storage/app/public/capturas/1_11.png');
    // $largura1 = 640;
    // $largura2 = 400;//Largura nova
    // $prop = (100*$largura2/$largura1)/100;
    // echo 'Largura: '.$prop*$largura1.'<BR>'; //Aqui óbvio vai dar 400
    // echo 'Altura: '.$prop*480; //Aqui vai dar 300
    die;
    // $st = new App\Http\Controllers\StorageController();
    $video = App\Models\Video::find(1);
    $info = pathinfo($video->buscarCaminhoCompleto());
    // $st->gerarCapturas($video);
    echo date('Y-m-d', filemtime(Illuminate\Support\Facades\Storage::disk('videos')->url($video->buscarCaminhoCompleto())));
    // echo hash_file('crc32', $d);
    die;
    // echo App\Http\Controllers\StorageController::teste($video);
    // $media = FFMpeg::fromDisk('videos')->open($video->buscarCaminhoCompleto());
    // echo $media->getDurationInSeconds();
    // echo "\r\n";
    // $video = App\Models\Video::find(2);
    // echo App\Http\Controllers\StorageController::teste($video);
    // $media2 = FFMpeg::fromDisk('videos')->open($video2->buscarCaminhoCompleto());
    // echo $media2->getDurationInSeconds();
    // die;
    // $probe = FFMpeg\FFProbe::create([
    //     'ffmpeg.binaries'  => 'I:/tools/ffmpeg/bin/ffmpeg.exe',
    //     'ffprobe.binaries' => 'I:/tools/ffmpeg/bin/ffprobe.exe'
    // ]);
    // $video = App\Models\Video::find(1);
    // $arquivo = $video->buscarCaminhoCompleto();
    // $video_info = $probe->streams(Storage::disk('videos')->url($arquivo))->videos()->first();
    // print_r($video_info);//->get('duration');
    // die;

    // $media = FFMpeg::fromDisk('videos')->open($video->buscarCaminhoCompleto());
    // $tempo = $media->getDurationInSeconds() / 7;
    // $frames = [
    //     1 => $tempo * 1,
    //     2 => $tempo * 2,
    //     3 => $tempo * 3,
    //     4 => $tempo * 4,
    //     5 => $tempo * 5,
    //     6 => $tempo * 6
    // ];
    // $media
    // ->getFrameFromSeconds($frames[1])->export()->toDisk('public')->save('capturas/'.$video->id.'_1.png')
    // ->getFrameFromSeconds($frames[2])->export()->toDisk('public')->save('capturas/'.$video->id.'_2.png')
    // ->getFrameFromSeconds($frames[3])->export()->toDisk('public')->save('capturas/'.$video->id.'_3.png')
    // ->getFrameFromSeconds($frames[4])->export()->toDisk('public')->save('capturas/'.$video->id.'_4.png')
    // ->getFrameFromSeconds($frames[5])->export()->toDisk('public')->save('capturas/'.$video->id.'_5.png')
    // ->getFrameFromSeconds($frames[6])->export()->toDisk('public')->save('capturas/'.$video->id.'_6.png');
    // die;

    // try {
    //     $f = Illuminate\Support\Facades\Storage::disk('videos')->url("kpop/Blackpink/Lives/BLACKPINK Live Broadcast! a-nation 2019 Osaka DAY1 (BS-Sky PerfecTV! 2019.08.17).ts");
    //     $file = BigFileTools\BigFileTools::createDefault()->getFile($f);
    //     echo "This file has " . $file->getSize() . " bytes\n";
    // }
    // catch (BigFileTools\Driver\AggregateException $ex) {
        
    // }
    // die;
    // echo filesize(Illuminate\Support\Facades\Storage::disk('videos')->url("kpop/Girl's Generation/Lives/170416 태연 1st 앨범 'My Voice (Deluxe Edition)' 분당 팬사인회 직캠 by DaftTaengk.mkv"));
    // echo "\r\n";
    // echo  4*1024*1024*1024 + $file_size;
    // echo Storage::disk('videos')->size(\App\Models\Video::find(361)->caminho.'/'.\App\Models\Video::find(361)->titulo.'.'.\App\Models\Video::find(361)->extensao);
    // die;
    // $files = Storage::disk('videos')->files('kpop/IU');
    // print_r($files);
    // die;
    // $directories = Storage::disk('videos')->allDirectories('kpop');
    // print_r($directories);
    // die;
    // $d = Illuminate\Support\Facades\Storage::disk('videos')->url($video->buscarCaminhoCompleto());
    // echo hash_file('crc32', $d);
    // die;

    // detalhes
    // [index] => 0
    // [codec_name] => vp9
    // [codec_long_name] => Google VP9
    // [profile] => Profile 0
    // [codec_type] => video
    // [codec_time_base] => 1001/24000
    // [codec_tag_string] => [0][0][0][0]
    // [codec_tag] => 0x0000
    // [width] => 1280
    // [height] => 720
    // [coded_width] => 1280
    // [coded_height] => 720
    // [has_b_frames] => 0
    // [sample_aspect_ratio] => 1:1
    // [display_aspect_ratio] => 16:9
    // [pix_fmt] => yuv420p
    // [level] => -99
    // [color_range] => tv
    // [color_space] => bt709
    // [refs] => 1
    // [r_frame_rate] => 24000/1001
    // [avg_frame_rate] => 24000/1001
    // [time_base] => 1/1000
    // [start_pts] => 0
    // [start_time] => 0.000000
    // [disposition] => Array
    // (
    // [default] => 1
    // [dub] => 0
    // [original] => 0
    // [comment] => 0
    // [lyrics] => 0
    // [karaoke] => 0
    // [forced] => 0
    // [hearing_impaired] => 0
    // [visual_impaired] => 0
    // [clean_effects] => 0
    // [attached_pic] => 0
    // [timed_thumbnails] => 0
    // $video = FFMpeg::fromDisk('videos')->open('jpop/Ayaka/1.mp4');
    // // print_r($video->getStreams());
    // // $video = FFMpeg::fromDisk('videos')->open('kpop/Taeyeon/Taeyeon and the screaming Sones.mkv');
    // echo $video->getDurationInSeconds();
    // //fazer filtrar todos os streams
    // echo $codec = $video->getStreams()[0]->get('codec_name');

    // // capturas
    // $contents = $video
    // ->getFrameFromSeconds(2)
    // ->export()
    // ->toDisk('public')
    // ->save('a.png')
    // ->getFrameFromSeconds(4)
    // ->export()
    // ->toDisk('public')
    // ->save('b.png');

    $video = App\Models\Video::find(1);
    $d = Illuminate\Support\Facades\Storage::disk('videos')->url($video->buscarCaminhoCompleto());
    $video2 = App\Models\Video::find(2);
    $d2 = Illuminate\Support\Facades\Storage::disk('videos')->url($video2->buscarCaminhoCompleto());
    $configuration = array(
        'ffmpeg.binaries'  => 'I:/tools/ffmpeg/bin/ffmpeg.exe',
        'ffprobe.binaries' => 'I:/tools/ffmpeg/bin/ffprobe.exe'
    );

    $ffprobe = FFMpeg\FFProbe::create($configuration);
    echo $ffprobe->format($d)->get('duration');
    echo $ffprobe->format($d2)->get('duration');
    print_r(
        $ffprobe
    ->streams($d)   // extracts streams informations
    ->videos()
    );
    // ->first()
    // ->get('duration');

    // $ffprobe = FFMpeg\FFProbe::create();
    // $ffprobe->isValid('/path/to/file/to/check'); // returns bool

    // print_r($ffprobe
    // ->streams( $d )   // extracts streams informations
    // ->audios()                      // filters video streams
    // ->first()       );
});