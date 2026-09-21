<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StatsController;


/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('stats.index');
});


/*
|--------------------------------------------------------------------------
| Existing Product Route
|--------------------------------------------------------------------------
*/

Route::get(
    '/products',
    [ProductController::class, 'index']
)->name('products.index');


/*
|--------------------------------------------------------------------------
| Laravel Statistics
|--------------------------------------------------------------------------
*/

Route::prefix('stats')
    ->name('stats.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Statistics Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [StatsController::class, 'index']
        )->name('index');


        /*
        |--------------------------------------------------------------------------
        | Generate New Scan
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/scan',
            [StatsController::class, 'scan']
        )->name('scan');


        /*
        |--------------------------------------------------------------------------
        | Statistics History
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/history',
            [StatsController::class, 'history']
        )->name('history');


        /*
        |--------------------------------------------------------------------------
        | Compare Two Scans
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/compare/{oldScan}/{newScan}',
            [StatsController::class, 'compare']
        )->name('compare');


        /*
        |--------------------------------------------------------------------------
        | Delete Historical Scan
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/history/{scan}',
            [StatsController::class, 'destroy']
        )->name('destroy');
    });