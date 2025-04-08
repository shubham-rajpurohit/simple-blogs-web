<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('posts', PostController::class);
    Route::get('/my-posts', [PostController::class, 'myPosts']);
    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
    Route::post('/posts/{post}/like', [LikeController::class, 'store']);
    Route::delete('/posts/{post}/like', [LikeController::class, 'destroy']);
});
