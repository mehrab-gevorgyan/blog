<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

Route::redirect('/', '/posts');

// resource controller
Route::resource('posts', PostController::class);

// get all posts by user, tag
Route::get('user/{id}', [PostController::class, 'userAllPosts']);
Route::get('tag/{id}', [PostController::class, 'tagAllPosts']);

Route::middleware(['guest'])->group(function() {
    // registration
    Route::get('/registration', [RegisterController::class, 'create'])->name('reg.create');
    Route::post('/registration', [RegisterController::class, 'store'])->name('reg.store');

    // login
    Route::get('/login', [LoginController::class, 'login'])->name('login');
});

Route::middleware(['auth'])->group(function() {
    // logout
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    // add like, dislike
    Route::post('/posts/{id}/like', [PostController::class, 'postLike'])->name('like');
    Route::post('/posts/{id}/dislike', [PostController::class, 'postDislike'])->name('dislike');

    // emoji
    Route::post('posts/{id}/emoji', [PostController::class, 'emoji'])->name('emoji');

    // add comment
    Route::post('posts/{id}/addComment', [PostController::class, 'addComment'])->name('addComment');
});



