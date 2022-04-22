<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlShortenerController;

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

Route::get('/', [UrlShortenerController::class, 'index'])->name('home');
Route::get('/{hash}', [UrlShortenerController::class, 'show'])->name('show');
Route::post('/shorten', [UrlShortenerController::class, 'shorten'])->name('shorten');
