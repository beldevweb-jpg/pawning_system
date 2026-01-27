<?php

use Illuminate\Support\Facades\Route;
use Modules\Commerce\Http\Controllers\CommerceController;

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::resource('commerces', CommerceController::class)->names('commerce');
// });

Route::group([], function () {
    route::get('/create_search', [CommerceController::class, 'create_search'])->name('commerce.create_search');
    route::post('/store_create_search', [CommerceController::class, 'store_create_search'])->name('commerce.store_create_search');
    route::get('/create_type_of_sale', [CommerceController::class, 'create_type_of_sale'])->name('commerce.create_type_of_sale');
    route::post('/store_create_type_of_sale', [CommerceController::class, 'store_create_type_of_sale'])->name('commerce.store_create_type_of_sale');
    route::get('/create_pawning/{id}', [CommerceController::class, 'create_pawning'])->name('commerce. ');
    Route::post('/commerce/pawning/{id}/update', [CommerceController::class, 'update_pawning'])
        ->name('commerce.update_pawning');
    route::get('/customer', [CommerceController::class, 'customer'])->name('commerce.customer');
    route::delete('/destroy/{id}', [CommerceController::class, 'destroy'])->name('commerce.destroy');
});
