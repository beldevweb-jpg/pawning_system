<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Modules\Commerce\Http\Controllers\CommerceController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return redirect()->route('commerce.create_search');
})->middleware(['auth', 'verified'])->name('dashboard');;

Route::middleware('auth')->group(function () {
    route::get('/user', [UserController::class, 'index'])->name('user.index');
    route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
});

require __DIR__ . '/auth.php';
