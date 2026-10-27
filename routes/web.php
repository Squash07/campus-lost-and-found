<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public Feed & Item Detail
Route::get('/', [ItemController::class, 'index'])->name('items.index');
// whereNumber keeps "/items/create" from being captured as an item id
Route::get('/items/{item}', [ItemController::class, 'show'])->whereNumber('item')->name('items.show');

// Authenticated Item & Claim actions
Route::middleware('auth')->group(function () {
    Route::resource('items', ItemController::class)->except(['index', 'show']);
    Route::patch('/items/{item}/returned', [ItemController::class, 'markReturned'])->name('items.returned');

    Route::post('/items/{item}/claims', [ClaimController::class, 'store'])->name('claims.store');
    Route::patch('/claims/{claim}/approve', [ClaimController::class, 'approve'])->name('claims.approve');
    Route::patch('/claims/{claim}/reject', [ClaimController::class, 'reject'])->name('claims.reject');

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Category Management
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', AdminCategoryController::class)->except('show');
});

require __DIR__.'/auth.php';
