<?php

use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\Product_ReviewsController;
use App\Http\Controllers\API\V1\ProductController;
use App\Http\Controllers\API\V1\Shipping_AddressesController;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\WishlistController;
use Illuminate\Http\Request;
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

Route::prefix('v1')->group(function () {

    //no-login required routes
    Route::post("users/login", [UserController::class, "login"]);
    Route::post("users/signup", [UserController::class, "signup"]);

    //login required routes
    Route::middleware('auth:sanctum')->group(function () {
        //Users routes
        Route::delete("users/logout", [UserController::class, "logout"]);
        Route::apiResource("users", UserController::class)
            ->except(["index", "store"])->name("show", "me");

        //Shipping adresses routes
        Route::apiResource("addresses", Shipping_AddressesController::class)
            ->except(["show, index"]);

        //Whishlist routes
        Route::apiResource("whishlist", WishlistController::class)
            ->except(["show", "update"]);

        //Categories routes
        Route::apiResource("categories", CategoryController::class)
            ->only(["index", "show"]);

        //Products routes
        Route::apiResource("products", ProductController::class)
            ->only(["index", "show"]);

        //Products' reviews routes
        Route::apiResource("products/reviews", Product_ReviewsController::class)
            ->except(["show"]);

        //Orders routes
        Route::apiResource("orders", OrderController::class)
            ->except(["show", "update"]);
    });
});
