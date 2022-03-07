<?php

return [
    'ffmpeg.binaries' => env('FFMPEG_BINARIES', '/usr/bin/ffmpeg'),
    'ffmpeg.threads' => env('FFMPEG_THREADS', 24),

    'ffprobe.binaries' => env('FFPROBE_BINARIES', '/usr/bin/ffprobe'),

    'timeout' => 3600,

    'log_channel' => env('LOG_CHANNEL', 'stack'),   // set to false to completely disable logging

    'temporary_files_root' => env('FFMPEG_TEMPORARY_FILES_ROOT', sys_get_temp_dir()),
];
