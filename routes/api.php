<?php

use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReactionController;
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
Route::get('/check-username/{username}', [UserController::class, 'checkUsernameAvailability']);
Route::patch('/post/{post}/pin', [PostController::class, 'pin']);
Route::get('/user/{username}', [UserController::class, 'getUserByUsername']);
//Route::middleware('auth:sanctum')->get('/user/{username}', [UserController::class, 'getUserByUsername']);
Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'store']);

Route::post('/forgot-password', [UserController::class, 'restore'])->middleware('guest', 'throttle:6,1');
Route::post('/reset-password', [UserController::class, 'newpassword'])->middleware('guest');

Route::apiResource('post', App\Http\Controllers\PostController::class);

Route::apiResource('media', App\Http\Controllers\MediaController::class);


// Ruta para solicitar reenvío de correo de verificación
Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
     ->middleware(['auth:sanctum', 'throttle:6,1'])
     ->name('verification.send');

// Ruta para verificar el correo electrónico
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
     ->middleware(['auth:sanctum', 'signed'])
     ->name('verification.verify');



Route::get('/explore', [PostController::class, 'explore'])->middleware('auth:sanctum');
Route::get('/explore/recommended', [PostController::class, 'recommended'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->post('/user/{user}', [UserController::class, 'update']);

Route::middleware('auth:sanctum')->get('/user/{user}/images', [MediaController::class, 'getUserImages']);

Route::post('/user/{user}/follow', [UserController::class, 'toggleFollow'])->middleware('auth:sanctum');

Route::get('/user/follows', [UserController::class, 'follows']);

Route::get('/user/{userId}/follow-data', [UserController::class, 'followData'])->middleware('auth:sanctum');
Route::get('/notifications/unread', [NotificationController::class, 'unreadCount']);
Route::patch('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
Route::post('/notifications/{notification}/accept', [UserController::class, 'acceptFollowRequest'])->middleware('auth:sanctum');
Route::post('/notifications/{notification}/reject', [UserController::class, 'rejectFollowRequest'])->middleware('auth:sanctum');
Route::post('/users/{user}/block', [UserController::class, 'blockUser']);
Route::delete('/users/{user}/unblock', [UserController::class, 'unblockUser']);
Route::get('/users/{user}/is-blocked', [UserController::class, 'checkIfBlocked']);
Route::post('/users/{userId}/toggle-block', [UserController::class, 'toggleBlock']);

Route::post('/verify-2fa', [UserController::class, 'verify2FA'])->middleware('throttle:6,1');

Route::post('/post/{post}/reactions', [ReactionController::class, 'store'])->middleware('auth:sanctum');
Route::post('/post/{post}/comments', [CommentController::class, 'store'])->middleware('auth:sanctum');
// Dentro de routes/api.php
Route::post('/posts/{post}/comments/{comment}/likes', [CommentController::class, 'like']);
Route::middleware('auth:sanctum')->get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index']);
Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

Route::middleware('auth:sanctum')->patch('/user/{user}/privacy', [UserController::class, 'updatePrivacy']);

Route::middleware('auth:sanctum')->get('/users', [UserController::class, 'index']);
// Agrega esta línea en routes/api.php dentro del grupo de middleware 'auth:sanctum'





Route::patch('/notifications/{notification}', [NotificationController::class, 'update']);


Route::apiResource('comment', App\Http\Controllers\CommentController::class);

Route::apiResource('reaction', App\Http\Controllers\ReactionController::class);

Route::apiResource('interaction-history', App\Http\Controllers\InteractionHistoryController::class);

Route::apiResource('story', App\Http\Controllers\StoryController::class);

Route::apiResource('view', App\Http\Controllers\ViewController::class);

Route::apiResource('message', App\Http\Controllers\MessageController::class);



Route::apiResource('album', App\Http\Controllers\AlbumController::class);

Route::apiResource('photo', App\Http\Controllers\PhotoController::class);

Route::apiResource('tag', App\Http\Controllers\TagController::class);

Route::apiResource('post-tag', App\Http\Controllers\PostTagController::class);

Route::apiResource('hashtag', App\Http\Controllers\HashtagController::class);

Route::apiResource('post-hashtag', App\Http\Controllers\PostHashtagController::class);

Route::apiResource('shop-item', App\Http\Controllers\ShopItemController::class);

Route::apiResource('purchase', App\Http\Controllers\PurchaseController::class);
