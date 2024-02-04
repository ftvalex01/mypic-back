<?php

use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'profile']);
    Route::post('/logout', [UserController::class, 'destroy']);
});
Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'store']);

Route::post('/forgot-password', [UserController::class, 'restore'])->middleware('guest','throttle:6,1');
Route::post('/reset-password', [UserController::class, 'newpassword'])->middleware('guest');

// Ruta para solicitar reenvío de correo de verificación
Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
     ->middleware(['auth:sanctum', 'throttle:6,1'])
     ->name('verification.send');
   
// Ruta para verificar el correo electrónico
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
     ->middleware(['auth:sanctum', 'signed'])
     ->name('verification.verify');




Route::middleware('auth:sanctum')->put('/user/{user}', [UserController::class, 'update']);



Route::apiResource('user-follower', App\Http\Controllers\UserFollowerController::class);

Route::apiResource('user-following', App\Http\Controllers\UserFollowingController::class);

Route::apiResource('post', App\Http\Controllers\PostController::class);

Route::apiResource('media', App\Http\Controllers\MediaController::class);

Route::apiResource('comment', App\Http\Controllers\CommentController::class);

Route::apiResource('reaction', App\Http\Controllers\ReactionController::class);

Route::apiResource('interaction-history', App\Http\Controllers\InteractionHistoryController::class);

Route::apiResource('story', App\Http\Controllers\StoryController::class);

Route::apiResource('view', App\Http\Controllers\ViewController::class);

Route::apiResource('message', App\Http\Controllers\MessageController::class);

Route::apiResource('notification', App\Http\Controllers\NotificationController::class);

Route::apiResource('album', App\Http\Controllers\AlbumController::class);

Route::apiResource('photo', App\Http\Controllers\PhotoController::class);

Route::apiResource('tag', App\Http\Controllers\TagController::class);

Route::apiResource('post-tag', App\Http\Controllers\PostTagController::class);

Route::apiResource('hashtag', App\Http\Controllers\HashtagController::class);

Route::apiResource('post-hashtag', App\Http\Controllers\PostHashtagController::class);

Route::apiResource('shop-item', App\Http\Controllers\ShopItemController::class);

Route::apiResource('purchase', App\Http\Controllers\PurchaseController::class);