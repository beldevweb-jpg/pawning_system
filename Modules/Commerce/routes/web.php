<?php

use Illuminate\Support\Facades\Route;
use Modules\Commerce\Http\Controllers\CommerceController;

// Route::middleware(['auth', 'verified'])->group(function () {
//     route::get('/create_search', [CommerceController::class, 'create_search'])->name('commerce.create_search');
//     route::post('/store_create_search', [CommerceController::class, 'store_create_search'])->name('commerce.store_create_search');
//     route::get('/create_member', [CommerceController::class, 'create_member'])->name('commerce.create_member');
//     route::post('/store_create_member', [CommerceController::class, 'store_create_member'])->name('commerce.store_create_member');
//     route::get('/search_member', [CommerceController::class,], 'search_member')->name('commerce.search_member');
//     route::get('/create_type_of_sale/{id}', [CommerceController::class, 'create_type_of_sale'])->name('commerce.create_type_of_sale');
//     Route::post(
//         '/store_type_of_sale/{id}',
//         [CommerceController::class, 'store_create_type_of_sale']
//     )->name('commerce.store_type_of_sale');
//     route::get('/create_pawning/{id}', [CommerceController::class, 'create_pawning'])->name('commerce.create_pawning');
//     route::post('/store_pawning', [CommerceController::class, 'store_pawning'])->name('commerce.store_pawning');
//     route::get('/report_pawning', [CommerceController::class, 'report_pawning'])->name('commerce.report_pawning');
//     Route::get('/report_member/{id}', [CommerceController::class, 'report_member'])
//         ->name('commerce.report_member');
//     Route::resource('commerces', CommerceController::class)->names('commerce');
//     route::get('/dok/{id}', [CommerceController::class, 'dok'])->name('commerce.dok');
//     route::get('/tai/{id}', [CommerceController::class, 'tai'])->name('commerce.tai');
//     route::get('/pueam/{id}', [CommerceController::class, 'pueam'])->name('commerce.pueam');
//     route::get('/create_sellfront', [CommerceController::class, 'create_sellfront'])->name('commerce.create_sellfront');
// });

Route::group([], function () {
    route::get('/create_search', [CommerceController::class, 'create_search'])->name('commerce.create_search');
    route::post('/store_create_search', [CommerceController::class, 'store_create_search'])->name('commerce.store_create_search');

    route::get('/search_member', [CommerceController::class,], 'search_member')->name('commerce.search_member');


    route::get('/create_member', [CommerceController::class, 'create_member'])->name('commerce.create_member');
    route::post('/store_create_member', [CommerceController::class, 'store_create_member'])->name('commerce.store_create_member');

    route::get('/create_type_of_sale/{id}', [CommerceController::class, 'create_type_of_sale'])->name('commerce.create_type_of_sale');

    route::get('/create_pawning/{id?}', [CommerceController::class, 'create_pawning'])->name('commerce.create_pawning');
    route::post('/store_pawning/{id}', [CommerceController::class, 'store_pawning'])->name('commerce.store_pawning');

    route::get('/report_pawning/{id?}', [CommerceController::class, 'report_pawning'])->name('commerce.report_pawning');
    Route::get('/report_member/{id}', [CommerceController::class, 'report_member'])
        ->name('commerce.report_member');

    Route::resource('commerces', CommerceController::class)->names('commerce');

    route::get('/dok/{id?}', [CommerceController::class, 'dok'])->name('commerce.dok');
    Route::post('/dok/store/{id?}', [CommerceController::class, 'dok_store'])
        ->name('commerce.dok_store');

    route::get('/tai/{id?}', [CommerceController::class, 'tai'])->name('commerce.tai');
    route::post('/tai/store', [CommerceController::class, 'tai_store'])
        ->name('commerce.tai_store');


    route::get('/pueam/{id?}', [CommerceController::class, 'pueam'])->name('commerce.pueam');
    route::post('/pueam/store', [CommerceController::class, 'store_pueam'])
        ->name('commerce.pueam_store');

    route::get('/create_sellfront/{id}', [CommerceController::class, 'create_sellfront'])->name('commerce.create_sellfront');
    Route::post('/sellfront/store', [CommerceController::class, 'store_sellfront'])
        ->name('commerce.store_sellfront');

    route::get('/sellfront/report', [CommerceController::class, 'report_sellfront'])->name('commerce.report_sellfront');
    route::get('/sale_list', [CommerceController::class, 'sale_list'])->name('commerce.sale_list');

    route::get('/sale/{id}', [CommerceController::class, 'show_sale'])->name('commerce.show_sale');
});
