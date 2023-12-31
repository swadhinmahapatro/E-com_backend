<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\productController;
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

Route::post('/register', [UserController::class, 'register']);

Route::post('/login', [UserController::class, 'login']);

Route::group(['middleware'=>'auth:api'], function(){
    Route::get('/userdetails',[UserController::class,'userDetails']);

    Route::get('/getproducts',[productController::class,'getProducts']);

    Route::post('/addproduct',[productController::class,'addProduct']);

    Route::delete('/deleteproduct/{id}',[productController::class,'deleteProduct']);

    Route::get("/getuser/{id}",[UserController::class,'getUserById']);

    Route::post("/updateuser/{id}",[UserController::class,'updateUserDetailsById']);
});



