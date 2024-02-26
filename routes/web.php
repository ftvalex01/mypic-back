<?php

use App\Http\Controllers\GithubController;
use App\Http\Controllers\GoogleController;
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

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});


Route::controller(GoogleController::class)->group(function () {
    Route::get('auth/redirect/google', 'redirectToGoogle')->name('auth.google');
    Route::get('auth/callback/google', 'handleGoogleCallback');
});

// Ruta para redirigir al usuario a GitHub
Route::get('/auth/redirect/github', [GithubController::class, 'redirectToProvider']);

// Ruta para manejar la respuesta de GitHub después de la autenticación
Route::get('/auth/callback/github', [GithubController::class, 'handleProviderCallback']);
