<?php

return [
    'ffmpeg' => [
        'binaries' => env('FFMPEG_BINARIES', 'I:/tools/ffmpeg/bin/ffmpeg.exe'),
        'threads'  => 12,
    ],

    'ffprobe' => [
        'binaries' => env('FFPROBE_BINARIES', 'I:/tools/ffmpeg/bin/ffprobe.exe'),
    ],

    'timeout' => 3600,

    'enable_logging' => true,
];
