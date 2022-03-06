<?php

return [
    'uploadInput' => env('INPUT_UPLOAD_STORAGE_LOCATION', storage_path('app/raw/Upload')),
    'downloadInput' => env('INPUT_DOWNLOAD_STORAGE_LOCATION', storage_path('app/raw/Download')),
    'youtubeInput' => env('INPUT_YOUTUBE_STORAGE_LOCATION', storage_path('app/raw/Youtube')),

    'uploadResult' => env('RESULT_UPLOAD_STORAGE_LOCATION', storage_path('app/converted/Upload')),
    'downloadResult' => env('RESULT_DOWNLOAD_STORAGE_LOCATION', storage_path('app/converted/Download')),
    'youtubeResult' => env('RESULT_YOUTUBE_STORAGE_LOCATION', storage_path('app/converted/Youtube'))
];
