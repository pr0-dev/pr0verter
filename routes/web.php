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


/**
 * Convert Group -> Used for either requesting a download or upload a file and get the status of the conversion.
 */
Route::prefix('convert')->group(function () {
    /** Main converter Page - all the settings are done here */
    Route::get('/', [ConverterController::class, 'home'])->name('home');
    /** Send the request there */
    Route::post('/', [ConverterController::class, 'convertRequest'])->name('convertRequest');
    /** Youtube data about available subtitles etc */
    Route::get('yt-info', [ConverterController::class, 'ytInfo'])->name('ytInfo');
    /** Show progress, give updates etc. */
    Route::get('{id}/progress', [ConverterController::class, 'progress'])->name('progress');
    /** Show the final result with embedded video */
    Route::get('{id}/show', [ConverterController::class, 'show'])->name('show');
    /** Download route for direct download or browser download */
    Route::get('{id}/download', [ConverterController::class, 'download'])->name('download');

});
