<?php

use Illuminate\Support\Facades\Route;
use Modules\Commerce\Http\Controllers\CommerceController;

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::resource('commerces', CommerceController::class)->names('commerce');
// });

Route::group([], function () {
    route::get('/create_search', [CommerceController::class, 'create_search'])->name('commerce.create_search');
    route::post('/store_create_search', [CommerceController::class, 'store_create_search'])->name('commerce.store_create_search');
    route::get('/create_member', [CommerceController::class, 'create_member'])->name('commerce.create_member');
    route::post('/store_create_member', [CommerceController::class, 'store_create_member'])->name('commerce.store_create_member');
    route::get('/create_type_of_sale/{id}', [CommerceController::class, 'create_type_of_sale'])->name('commerce.create_type_of_sale');
    Route::post(
        '/store_type_of_sale/{id}',
        [CommerceController::class, 'store_create_type_of_sale']
    )->name('commerce.store_type_of_sale');
    route::get('/create_pawning/{id}', [CommerceController::class, 'create_pawning'])->name('commerce.create_pawning');
    route::post('/store_pawning', [CommerceController::class, 'store_pawning'])->name('commerce.store_pawning');
    route::get('/report_pawning', [CommerceController::class, 'report_pawning'])->name('commerce.report_pawning');
    Route::get('/customer/{id}', [CommerceController::class, 'customer'])
        ->name('commerce.customer');
});
