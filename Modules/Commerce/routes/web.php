<?php

use Illuminate\Support\Facades\Route;
use Modules\Commerce\Http\Controllers\CommerceController;

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::resource('commerces', CommerceController::class)->names('commerce');
// });

Route::group([], function () {
    Route::get('/commerce', [CommerceController::class, 'index'])->name('commerce.index');
    Route::get('/sels', [CommerceController::class, 'index'])->name('sels.create_seles');
    Route::Post('/create_type', [CommerceController::class, 'create_type'])->name('commerce.create_type');
    Route::Post('/create_pawning', [CommerceController::class, 'create_pawning'])->name('commerce.create_pawning');
});
