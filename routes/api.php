<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Product\CategoryController;
use App\Http\Controllers\Product\TypesController;
use App\Http\Controllers\Product\BrandController;
use App\Http\Controllers\Product\AttributeController;
use App\Http\Controllers\Product\AttributeSetController;
use App\Http\Controllers\Product\ProductController;
use Illuminate\Support\Facades\Route;

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
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::middleware(['auth:api'])->group(function () {

    Route::resource('/product/attribute', AttributeController::class);
    Route::resource('/product/attribute-set', AttributeSetController::class);
    Route::resource('/product/brand', BrandController::class);
    Route::resource('/product/types', TypesController::class);

    Route::get('/product/category/tree', [CategoryController::class, 'category_tree']);
    Route::resource('/product/category', CategoryController::class);

    Route::resource('/product', ProductController::class);
});

/**
 * API Auth
 */
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/refresh', [AuthController::class, 'refresh']);
Route::post('/auth/logout', [AuthController::class, 'logout']);
Route::get('/auth/me', [AuthController::class, 'me']);
/**
 * /API Auth
 */
