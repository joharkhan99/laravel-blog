<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', PostController::class . '@index');
Route::get('/home', ['as' => 'home', 'uses' => PostController::class . '@index']);

//authentication
// Route::resource('auth', 'Auth\AuthController');
// Route::resource('password', 'Auth\PasswordController');
Route::get('/logout', UserController::class . '@logout');
Route::group(['prefix' => 'auth'], function () {
    Auth::routes();
});
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// check if logged in user and then user can go to below routes
Route::middleware(['auth'])->group(function () {
    // show new post form
    Route::get('new-post', PostController::class . '@create');
    // save post
    Route::get('new-post', PostController::class . '@store');
    // edit post
    Route::get('edit/{slug}', PostController::class . '@edit');
    // update post
    Route::get('update', PostController::class . '@update');
    // delete post
    Route::get('delete/{id}', PostController::class . '@destroy');
    // display user's all posts
    Route::get('my-all-posts', UserController::class . '@user_posts_all');
    // display user's deafts
    Route::get('my-drafts', UserController::class . '@user_posts_draft');
    // add comment
    Route::get('comment/add', CommentController::class . '@store');
    // delete comment
    Route::get('comment/delete/{id}', CommentController::class . '@destroy');
});

// users profile
Route::get('user/{id}', UserController::class . '@profile')->where('id', '[0-9]+');
// display list of posts
Route::get('user/{id}/posts', UserController::class . '@user_posts')->where('id', '[0-9]+');
// display singgle post
Route::get('/{slug}', ['as' => 'post', 'uses' => PostController::class . '@show'])->where('slug', '[A-Za-z0-9-_]+');
