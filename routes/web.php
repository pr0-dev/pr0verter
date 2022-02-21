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

Route::get('/', [StaticsController::class, 'welcome'])->name('welcome');
Route::get('changelog', [StaticsController::class, 'changelog'])->name('changelog');
Route::get('contact', [StaticsController::class, 'contact'])->name('contact');
Route::get('convert', [ConverterController::class, 'home'])->name('home');
Route::post('convert', [ConverterController::class, 'convertRequest'])->name('convertRequest');
Route::get('convert/{id}/progress', [ConverterController::class, 'progress'])->name('progress');
Route::get('convert/{id}/show', [ConverterController::class, 'show'])->name('show');
Route::get('convert/{id}/download', [ConverterController::class, 'download'])->name('download');



require __DIR__.'/auth.php';
