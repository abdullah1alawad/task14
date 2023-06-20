<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\products\ProductController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OrderController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register',[AuthController::class,'register']);
Route::post('/logout',[AuthController::class,'logout']);
Route::post('/login',[AuthController::class,'login'])->name('login');

// ----------------------------products----------------------------------------
Route::group(['middleware'=>'auth:sanctum'],function (){
    Route::group(['middleware'=>'isAdmin'],function(){
        Route::delete('/delete-productByAdmin/{id}',[ProductController::class,'destroy']);
        Route::get('/all-users',[UserController::class,'index']);
    });

    Route::group(['middleware'=>'isVendor'],function(){
        Route::post('/add-product',[ProductController::class,'store']);
        Route::match(['put', 'patch'],'/update-product/{id}',[ProductController::class,'update']);
        Route::delete('/delete-product/{id}',[ProductController::class,'destroy']);
    });
    Route::get('/product/{id}',[ProductController::class,'show']);
    Route::get('/productSearch/{letter}',[ProductController::class,'filterProductsByCategory']);
    Route::get('/all-products',[ProductController::class,'index']);
    Route::get('/product/vendors/{id}',[ProductController::class,'getProductAndVendors']);
});

//-----------------------------categories ------------------------------------

Route::group(['middleware'=>'auth:sanctum'],function (){
    Route::group(['middleware'=>'isAdmin'],function(){
        Route::post('/add-category',[CategoryController::class,'store']);
        Route::match(['put','patch'],'/update-category/{id}',[CategoryController::class,'update']);
        Route::delete('/delete-category/{id}',[CategoryController::class,'destroy']);
    });
    Route::get('/category/{id}',[CategoryController::class,'show']);
    Route::get('/all-categories',[CategoryController::class,'index']);

});

//-----------------------------reviews---------------------------------------

Route::group(['middleware'=>'auth:sanctum'],function(){

    Route::post('/add-review',[ReviewController::class,'store']);

    Route::group(['middleware'=>'isAdmin'],function(){
        Route::get('/all-reviews',[ReviewController::class,'index']);
    });
    Route::group(['middleware'=>'sameUser'],function (){
        Route::match(['put','patch'],'/update-review/{id}',[ReviewController::class,'update']);
        Route::delete('/delete-review/{id}',[ReviewController::class,'destroy']);
    });
    Route::get('/all-user-reviews',[ReviewController::class,'showUserReviews']);
    Route::get('/all-product-reviews/{id}',[ReviewController::class,'show']);
});

//------------------------------------orders-------------------------------------
Route::group(['middleware'=>'auth:sanctum'],function(){
    Route::post('/add-order',[OrderController::class,'store']);
    Route::match(['put','patch'],'/update-order/{id}',[OrderController::class,'update']);
    Route::delete('/delete-order/{id}',[OrderController::class,'destroy']);
    Route::get('/user-orders',[OrderController::class,'show']);
    Route::get('/all-orders',[OrderController::class,'index']);
});

//-----------------------------------vendors----------------------------------------

//--------------------------------to be continued-----------------------------------

Route::group(['middleware'=>['auth:sanctum','isVendor']],function(){
    Route::post('/add-product-vendor/{id}',[UserController::class,'store']);
});

//  9|2crMoBHSAc1vUUgyYbT9OwMhnsNq7whcPwjR8fLD
