<?php

use Livewire\Livewire;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;

/* NOTE: Do Not Remove
/ Livewire asset handling if using sub folder in domain
*/
Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});
/*
/ END
*/

Route::get('/', [FrontController::class, 'index'])->name('front.index');

Route::get('/details/{article_news:slug}', [FrontController::class, 'details'])->name('front.details');

Route::get('/category/{category:slug}', [FrontController::class, 'category'])->name('front.category');

Route::get('/author/{author:slug}', [FrontController::class, 'author'])->name('front.author');

Route::get('/search', [FrontController::class, 'search'])->name('front.search');

Route::post('/details/{articleNews}/comment', [FrontController::class, 'comment'])->name('front.comment');

Route::post('/details/{comment}/reply', [FrontController::class, 'reply'])->name('front.reply');
