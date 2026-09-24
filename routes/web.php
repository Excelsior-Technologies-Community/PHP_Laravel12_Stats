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
        | Individual Scan Details
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/history/{scan}',
            [StatsController::class, 'show']
        )->name('show');


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


        /*
        |--------------------------------------------------------------------------
        | CSV Export
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/export/csv',
            [StatsController::class, 'exportCsv']
        )->name('export.csv');


        /*
        |--------------------------------------------------------------------------
        | JSON Export
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/export/json',
            [StatsController::class, 'exportJson']
        )->name('export.json');


        /*
        |--------------------------------------------------------------------------
        | NEW: Real-Time Code Quality Radar & Maintainability Index
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/code-quality',
            [StatsController::class, 'codeQuality']
        )->name('quality');


        /*
        |--------------------------------------------------------------------------
        | NEW: Multi-Scan Code Churn & Growth Trend Analytics
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/analytics',
            [StatsController::class, 'analytics']
        )->name('analytics');

        Route::get(
            '/analytics-json',
            [StatsController::class, 'analyticsJson']
        )->name('analytics.json');


        /*
        |--------------------------------------------------------------------------
        | NEW: Smart Architecture Health & Anti-Pattern Detector Engine
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/architecture-audit',
            [StatsController::class, 'architectureAudit']
        )->name('audit');

        Route::get(
            '/architecture-audit/export',
            [StatsController::class, 'exportAuditCsv']
        )->name('audit.export');
    });