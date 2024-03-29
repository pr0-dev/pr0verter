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
use App\Http\Controllers\StatisticsController;

Route::prefix('conversions')->controller(ConversionController::class)->group(function() {
    Route::get('/', 'listConversions')->name('listConversions');
    if(!config('pr0verter.disabled.module.upload'))
        Route::post('upload', 'storeUpload')->name('storeUpload');
    if(!config('pr0verter.disabled.module.youtube'))
        Route::post('youtube', 'storeYoutube')->name('storeYoutube');
    if(!config('pr0verter.disabled.module.download'))
        Route::post('download', 'storeDownload')->name('storeDownload');
    Route::get('{conversion}', 'showConversion')->name('showConversion');
    Route::patch('{conversion}', 'editConversion')->name('editConversion');
    Route::delete('{conversion}', 'deleteConversion')->name('deleteConversion');
    Route::get('{conversion}/download', 'downloadConversion')->name('downloadConversion');
    Route::get('{conversion}/view', 'viewConversion')->name('viewConversion');
});

Route::prefix('configurations')->controller(ConfigurationController::class)->group(function() {
    Route::get('settings', 'settings')->name('settings');
    Route::get('disabled-components', 'disabledComponents')->name('disabledComponents');
});

Route::prefix('repository')->controller(RepositoryController::class)->group(function() {
    Route::post('youtube-info', 'youtubeInfo')->name('youtubeInfo');
    Route::get('github-info', 'githubInfo')->name('githubInfo');
});

Route::prefix('stats')->controller(StatisticsController::class)->group(function() {
    Route::get('system', 'systemStats')->name('systemStats');
});
