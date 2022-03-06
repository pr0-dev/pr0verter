<?php

use App\Http\Controllers\ConverterController;
use App\Http\Controllers\StaticsController;
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

/** Landing Page */
Route::get('/', [StaticsController::class, 'welcome'])->name('welcome');

/** Changelog Page -> fetched from git repository directly */
Route::get('changelog', [StaticsController::class, 'changelog'])->name('changelog');

/** Contact Page */
Route::get('contact', [StaticsController::class, 'contact'])->name('contact');

Route::get('limits', [StaticsController::class, 'limits'])->name('limits');


/**
 * Convert Group -> Used for either requesting a download or upload a file and get the status of the conversion.
 */
Route::prefix('converter')->group(function () {
    /** Main converter Page - all the settings are done here */
    Route::get('/', [ConverterController::class, 'home'])->name('home');
    /** Send the upload request there */
    Route::post('upload', [ConverterController::class, 'convertUpload'])->name('convertUpload');
    /** Send the download request there */
    Route::post('downloadFromSite', [ConverterController::class, 'convertDownload'])->name('convertDownload');
    /** Send the youtube-download request there */
    Route::post('downloadFromYoutube', [ConverterController::class, 'convertYoutube'])->name('convertYoutube');
    /** Youtube data about available subtitles etc */
    Route::get('yt-info', [ConverterController::class, 'ytInfo'])->name('ytInfo');
    /** Show progress */
    Route::get('progress/{guid}', [ConverterController::class, 'progress'])->name('progress');
    /** Send updates on the progress */
    Route::get('progress/info/{guid}', [ConverterController::class, 'progressInfo'])->name('progressInfo');
    /** Show the final result with embedded video */
    Route::get('show/{guid}', [ConverterController::class, 'show'])->name('show');
    /** Download route for direct download or browser download */
    Route::get('download/{guid}', [ConverterController::class, 'download'])->name('download');

});
