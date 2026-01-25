<?php

use Illuminate\Support\Facades\Route;
use Modules\Commerce\Http\Controllers\CommerceController;

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::resource('commerces', CommerceController::class)->names('commerce');
// });

Route::group([], function () {
    route::get('/create_type_of_sale', [CommerceController::class, 'create_type_of_sale'])->name('commerce.create_type_of_sale');
    route::post('/store_create_type_of_sale', [CommerceController::class, 'store_create_type_of_sale'])->name('commerce.store_create_type_of_sale');
    route::get('/create_pawning', [CommerceController::class, 'create_pawning'])->name('commerce.create_pawning');
    route::post('/store_create_pawming', [CommerceController::class, 'store_create_pawming'])->name('commerce.store_create_pawming');
});
