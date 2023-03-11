<?php

use Illuminate\Support\Facades\Route;

Route::get('/', App\Http\Controllers\MainController::class)->name('home');

// message

Route::post('/', App\Http\Controllers\Message\StoreController::class)->name('message.store');

Route::group(['prefix' => 'message'], function() {
    Route::get('/', App\Http\Controllers\Message\IndexController::class)->name('message.index');
});

///////////////

Route::middleware(['guest'])->group(function () {
    Route::get('/register', [App\Http\Controllers\UserController::class, 'create'])->name('register.create');
    Route::post('/register', [App\Http\Controllers\UserController::class, 'store'])->name('register.store');
    Route::get('/login', [App\Http\Controllers\UserController::class, 'loginForm'])->name('login.create');
    Route::post('/login', [App\Http\Controllers\UserController::class, 'login'])->name('login');
});


Route::get('/logout', [App\Http\Controllers\UserController::class, 'logout'])->name('logout')->middleware('auth');


Route::middleware(['admin'])->prefix('admin')->group(function () {

    Route::get('/', App\Http\Controllers\IndexController::class)->name('admin');

    Route::group(['prefix' => 'setings'], function() {
        Route::get('/', App\Http\Controllers\Seting\IndexController::class)->name('seting.index');
        Route::get('/create', App\Http\Controllers\Seting\CreateController::class)->name('seting.create');
        Route::post('/', App\Http\Controllers\Seting\StoreController::class)->name('seting.store');
        Route::get('/{seting}/edit', App\Http\Controllers\Seting\EditController::class)->name('seting.edit');
        Route::patch('/{seting}', App\Http\Controllers\Seting\UpdateController::class)->name('seting.update');
        Route::delete('/{seting}', App\Http\Controllers\Seting\DeleteController::class)->name('seting.delete');
    });

    Route::group(['prefix' => 'slider'], function() {
        Route::get('/', App\Http\Controllers\Slider\IndexController::class)->name('slider.index');
        Route::get('/create', App\Http\Controllers\Slider\CreateController::class)->name('slider.create');
        Route::post('/', App\Http\Controllers\Slider\StoreController::class)->name('slider.store');
        Route::get('/{slider}/edit', App\Http\Controllers\Slider\EditController::class)->name('slider.edit');
        Route::patch('/{slider}', App\Http\Controllers\Slider\UpdateController::class)->name('slider.update');
        Route::delete('/{slider}', App\Http\Controllers\Slider\DeleteController::class)->name('slider.delete');
    });

    Route::group(['prefix' => 'group'], function() {
        Route::get('/', App\Http\Controllers\Group\IndexController::class)->name('group.index');
        Route::get('/create', App\Http\Controllers\Group\CreateController::class)->name('group.create');
        Route::post('/', App\Http\Controllers\Group\StoreController::class)->name('group.store');
        Route::get('/{group}/edit', App\Http\Controllers\Group\EditController::class)->name('group.edit');
        Route::patch('/{group}', App\Http\Controllers\Group\UpdateController::class)->name('group.update');
        Route::delete('/{group}', App\Http\Controllers\Group\DeleteController::class)->name('group.delete');
    });

    Route::group(['prefix' => 'post'], function() {
        Route::get('/', App\Http\Controllers\Post\IndexController::class)->name('post.index');
        Route::get('/create', App\Http\Controllers\Post\CreateController::class)->name('post.create');
        Route::post('/', App\Http\Controllers\Post\StoreController::class)->name('post.store');
        Route::get('/{post}/edit', App\Http\Controllers\Post\EditController::class)->name('post.edit');
        Route::patch('/{post}', App\Http\Controllers\Post\UpdateController::class)->name('post.update');
        Route::delete('/{post}', App\Http\Controllers\Post\DeleteController::class)->name('post.delete');
    });


    Route::group(['prefix' => 'mainset'], function() {
        Route::get('/', App\Http\Controllers\MainSet\MainSetController::class)->name('mainset.index');
        Route::post('/edit', App\Http\Controllers\MainSet\MainSetEditController::class)->name('mainset.store');
        Route::patch('/edit', App\Http\Controllers\MainSet\MainSetEditController::class)->name('mainset.update');
    });
});


