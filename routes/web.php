<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

Route::redirect('/', '/posts');

// resource controller
Route::resource('posts', PostController::class);

// get all posts from user, tag
Route::get('user/{id}', [PostController::class, 'userAllPosts']);
Route::get('tag/{id}', [PostController::class, 'tagAllPosts']);

Route::middleware(['guest'])->group(function() {
    // registration
    Route::get('/registration', [UserController::class, 'create'])->middleware('guest')->name('reg.create');
    Route::post('/registration', [UserController::class, 'store'])->middleware('guest')->name('reg.store');

    // login
    Route::get('/login', [UserController::class, 'login'])->middleware('guest')->name('login');
});

Route::middleware(['auth'])->group(function() {
    // logout
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');

    // add like, dislike
    Route::post('/posts/{id}/like', [PostController::class, 'postLike'])->name('like');
    Route::post('/posts/{id}/dislike', [PostController::class, 'postDislike'])->name('dislike');

    // emoji
    Route::post('posts/{id}/emoji', [PostController::class, 'emoji'])->name('emoji');

    // add comment
    Route::post('posts/{id}/addComment', [PostController::class, 'addComment'])->name('addComment');
});



