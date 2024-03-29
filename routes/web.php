<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Conversion;
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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/faq', function () {
    return Inertia::render('FAQ', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/converter', function () {
    return Inertia::render('Converter', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/converter/{type}', function () {
    return Inertia::render('Converter', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/progress/{conversion}', function (Conversion $conversion) {
    return Inertia::render('Progress', [
        'conversion' => $conversion
    ]);
})->name("progress");

Route::get('/finished/{conversion}', function (Conversion $conversion) {
    return Inertia::render('Finished', [
        'conversion' => $conversion
    ]);
})->name("finished");

Route::get('/error/{conversion?}', function (Conversion $conversion) {
    return Inertia::render('Error', [
        'conversion' => $conversion
    ]);
})->name("error");

Route::get('/contact', function () {
    return Inertia::render('Contact');
})->name("contact");

Route::get('/changelog', function () {
    return Inertia::render('Changelog');
})->name("changelog");
