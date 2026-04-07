<?php

use Illuminate\Support\Facades\Route;
use Modules\Commerce\Http\Controllers\CommerceController;

Route::middleware(['auth', 'verified'])->group(function () {
    route::get('/create_search', [CommerceController::class, 'create_search'])->name('commerce.create_search');
    route::post('/store_create_search', [CommerceController::class, 'store_create_search'])->name('commerce.store_create_search');

    route::get('/search_member', [CommerceController::class, 'search_member'])->name('commerce.search_member');


    route::get('/create_member', [CommerceController::class, 'create_member'])->name('commerce.create_member');
    route::post('/store_create_member', [CommerceController::class, 'store_create_member'])->name('commerce.store_create_member');

    route::get('/create_type_of_sale/{id?}', [CommerceController::class, 'create_type_of_sale'])->name('commerce.create_type_of_sale');

    route::get('/create_pawning/{id?}', [CommerceController::class, 'create_pawning'])->name('commerce.create_pawning');
    route::post('/store_pawning/{id?}', [CommerceController::class, 'store_pawning'])->name('commerce.store_pawning');
    Route::get('/show/{id}', [CommerceController::class, 'show'])
        ->name('commerce.show');

    route::get('/report_pawning/{id?}', [CommerceController::class, 'report_pawning'])->name('commerce.report_pawning');

    Route::get('/report_member/{id}', [CommerceController::class, 'report_member'])
        ->name('commerce.report_member');

    // Route::resource('commerces', CommerceController::class)->names('commerce');

    route::get('/dok/{id?}', [CommerceController::class, 'dok'])->name('commerce.dok');
    Route::post('/dok/store/{id?}', [CommerceController::class, 'dok_store'])
        ->name('commerce.dok_store');

    route::get('/tai/{id?}', [CommerceController::class, 'tai'])->name('commerce.tai');
    route::post('/tai/store/{id?}', [CommerceController::class, 'tai_store'])
        ->name('commerce.tai_store');


    route::get('/pueam/{id?}', [CommerceController::class, 'pueam'])->name('commerce.pueam');
    route::post('/pueam/store/{id?}', [CommerceController::class, 'store_pueam'])
        ->name('commerce.pueam_store');

    route::get('/create_salefront/{id?}', [CommerceController::class, 'create_salefront'])->name('commerce.create_salefront');
    Route::post('/salefront/store', [CommerceController::class, 'store_salefront'])
        ->name('commerce.store_salefront');
    route::get('/report_salefront', [CommerceController::class, 'report_salefront'])->name('commerce.report_salefront');



    route::get('/sale_list', [CommerceController::class, 'sale_list'])->name('commerce.sale_list');

    route::get('/sale/{id}', [CommerceController::class, 'show_sale'])->name('commerce.show_sale');

    route::get('/slip/{id}', [CommerceController::class, 'slip'])->name('commerce.slip');

    route::get('/detil_sale/{id}', [CommerceController::class, 'detil_sale'])->name('commerce.detil_sale');
    Route::get(
        '/report-salefront-pdf',
        [CommerceController::class, 'reportsalefrontPdf']
    )->name('commerce.report_sale_pdf');

    Route::get('/sale-list-pdf', [CommerceController::class, 'saleListPdf'])
        ->name('commerce.sale_list_pdf');

    route::get('/show_member', [CommerceController::class, 'show_member'])->name('commerce.show_member');

    route::get('/edit_status_member/{id}', [CommerceController::class, 'edit_status_member'])->name('commerce.edit_status_member');
    route::post('/stor_edit_status_member/{id}', [CommerceController::class, 'stor_edit_status_member'])->name('commerce.stor_edit_status_member');

    Route::get('/manage_dok', [CommerceController::class, 'manage_dok'])
        ->name('commerce.manage_dok');

    Route::post('/stor_manage_dok/{id}', [CommerceController::class, 'stor_manage_dok'])
        ->name('commerce.stor_manage_dok');

    Route::get('/settings', [CommerceController::class, 'settings'])
        ->name('commerce.settings');

    Route::post('/settings/save/{id?}', [CommerceController::class, 'save_settings'])
        ->name('commerce.save_settings');
});
