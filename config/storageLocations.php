<?php

return [
    'uploadInput' => env('INPUT_UPLOAD_STORAGE_LOCATION', storage_path('raw/Upload')),
    'downloadInput' => env('INPUT_DOWNLOAD_STORAGE_LOCATION', storage_path('raw/Download')),
    'youtubeInput' => env('INPUT_YOUTUBE_STORAGE_LOCATION', storage_path('raw/Youtube')),

    'uploadResult' => env('RESULT_UPLOAD_STORAGE_LOCATION', storage_path('converted/Upload')),
    'downloadResult' => env('RESULT_DOWNLOAD_STORAGE_LOCATION', storage_path('converted/Download')),
    'youtubeResult' => env('RESULT_YOUTUBE_STORAGE_LOCATION', storage_path('converted/Youtube'))
];
