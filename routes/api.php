<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\ConversionController;
use App\Http\Controllers\RepositoryController;

Route::prefix('conversions')->controller(ConversionController::class)->group(function() {
    Route::get('/', 'listConversions')->name('listConversions');
    Route::get('{conversion}', 'showConversion')->name('showConversion');
    Route::patch('{conversion}', 'editConversion')->name('editConversion');
    Route::delete('{conversion}', 'deleteConversion')->name('deleteConversion');
    Route::post('upload', 'storeUpload')->name('storeUpload');
    Route::post('youtube', 'storeYoutube')->name('storeYoutube');
    Route::post('download', 'storeDownload')->name('storeDownload');
});

Route::prefix('configurations')->controller(ConfigurationController::class)->group(function() {
    Route::get('limits', 'limits')->name('limits');
    Route::get('disabled-components', 'disabledComponents')->name('disabledComponents');
});

Route::prefix('repository')->controller(RepositoryController::class)->group(function() {
    Route::post('youtube-info', 'youtubeInfo')->name('youtubeInfo');
    Route::get('github-info', 'githubInfo')->name('githubInfo');
});
