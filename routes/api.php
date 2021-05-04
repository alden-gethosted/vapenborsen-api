<?php

use App\Http\Controllers\Advertisement\AdMessageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Package\PackageController;
use App\Http\Controllers\Package\PackagePurchaseController;
use App\Http\Controllers\Product\CategoryController;
use App\Http\Controllers\Product\TagController;
use App\Http\Controllers\Product\TypesController;
use App\Http\Controllers\Product\BrandController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\Advertisement\AdPackageController;
use App\Http\Controllers\Advertisement\AdController;
use App\Http\Controllers\Advertisement\AdReviewController;
use App\Http\Controllers\Advertisement\AdFavouriteController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Product\AttributeController;
use App\Http\Controllers\Product\AttributeSetController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\User\UserController;
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
Route::post('/customer/register', [UserController::class, 'register']);

Route::middleware(['auth:api'])->group(function () {
    Route::resource('/product/attribute', AttributeController::class);
    Route::resource('/product/attribute-set', AttributeSetController::class);
    Route::resource('/product/brand', BrandController::class);
    Route::resource('/product/types', TypesController::class);
    Route::get('/product/category/tree', [CategoryController::class, 'category_tree']);
    Route::resource('/product/category', CategoryController::class);
    Route::resource('/product/tag', TagController::class);
    Route::resource('/area', AreaController::class);

   // Route::resource('/ads/package', AdPackageController::class);
    Route::get('/ads/message', [AdMessageController::class, 'index']);
    Route::get('/ads/message/{id}', [AdMessageController::class, 'message']);
    Route::post('/ads/message', [AdMessageController::class, 'store']);
    Route::delete('/ads/message', [AdMessageController::class, 'destroy']);

    Route::resource('/ads', AdController::class);

    Route::get('/package/my-purchase', [PackagePurchaseController::class, 'my_order']);
    Route::resource('/package/purchase', PackagePurchaseController::class);
    Route::resource('/package', PackageController::class);

    Route::resource('/ads.reviews', AdReviewController::class);
    Route::resource('/ads.favourite', AdFavouriteController::class);
    Route::resource('/users.companies', CompanyController::class);
    Route::resource('/product', ProductController::class);
    Route::resource('/coupons', CouponController::class);

    Route::resource('/customer', CustomerController::class);
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
