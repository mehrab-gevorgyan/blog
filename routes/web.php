<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

Route::redirect('/', '/posts');

// resource controller
Route::resource('posts', PostController::class);

// registration
Route::get('/registration', [UserController::class, 'create'])->name('reg.create');
Route::post('/registration', [UserController::class, 'store'])->name('reg.store');

// login
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

// like, dislike
Route::post('/posts/{id}/like', [PostController::class, 'postLike'])->name('like');
Route::post('/posts/{id}/dislike', [PostController::class, 'postDislike'])->name('dislike');

// emoji
Route::post('posts/{id}/emoji', [PostController::class, 'emoji'])->name('emoji');

// all posts from user, tag
Route::get('user/{id}', [PostController::class, 'userAllPosts']);
Route::get('tag/{id}', [PostController::class, 'tagAllPosts']);

// addcomment
Route::post('posts/{id}/addComment', [PostController::class, 'addComment'])->name('addComment');

