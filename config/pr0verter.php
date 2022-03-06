<?php

return [
    /** Minimum Result (Default 1 MB) */
    'minResultSize' => env('MIN_RESULT_SIZE', 1),
    /** Maximum Result (Default 200 MB) */
    'maxResultSize' => env('MAX_RESULT_SIZE', 200),
    /** Maximum Result length (Default 299 Seconds (1 Second Spare)) */
    'maxResultLength' => env('MAX_RESULT_LENGTH', 299),
    /** Maximum Result Megapixels (Default 20.25) */
    'maxResultPixels' => env('MAX_RESULT_MEGAPIXELS', 20.25),
    /** Minimum Audio Bitrate (Default 96) */
    'minResultAudioBitrate' => env('MIN_RESULT_AUDIO_BITRATE', 96),
    /** Maximum Audio Bitrate */
    'maxResultAudioBitrate' => env('MAX_RESULT_AUDIO_BITRATE', 255),
    /** Minimum Upload Size (Default 10 KB)*/
    'minUploadSize' => 10,
    /** Maximum Upload Size (Default 2GB) */
    'maxUploadSize' => env('MAX_UPLOAD_SIZE', 2097152),
];
