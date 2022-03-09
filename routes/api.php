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

use App\Http\Controllers\ConversionController;

Route::prefix('conversions')->controller(ConversionController::class)->group(function() {
    Route::get('/', 'listConversions')->name('listConversions');
    Route::get('{$conversion}', 'showConversion')->name('showConversion');
    Route::patch('{$conversion}', 'editConversion')->name('editConversion');
    Route::delete('{$conversion}', 'deleteConversion')->name('deleteConversion');
    Route::post('upload', 'storeUpload')->name('storeUpload');
    //Route::post('youtube', 'storeYoutube')->name('storeYoutube');
    //Route::post('download', 'storeDownload')->name('storeDownload');
});
