<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyResourcesController;
use App\Http\Controllers\CompletionsController;
use App\Http\Controllers\RagResourceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(CompanyController::class)->group(function () {
        Route::prefix('companies')->group(function () {
            Route::get('/', 'index');
            Route::get('/{id}', 'show');
        });
    });

    Route::controller(CompanyResourcesController::class)->group(function () {
        Route::prefix('company-resources')->group(function () {
            Route::get('/{id}', 'show');
        });
    });

    Route::controller(RagResourceController::class)->group(function () {
        Route::prefix('resources')->group(function () {
            Route::get('/', 'index');
            Route::get('/{id}', 'show');
        });
    });

    Route::controller(CompletionsController::class)->group(function () {
        Route::prefix('completions')->group(function () {
            Route::get('/', 'index');
        });
    });
// });
