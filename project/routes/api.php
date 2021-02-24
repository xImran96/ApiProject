<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;



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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('login', 'Api\AuthController@login');




Route::middleware('jwt.verify')->group(function () {
    
    Route::resource('/products', 'Api\ProductController');
    Route::resource('/invoices','Api\InvoiceController');
    Route::resource('/categories', 'Api\CategoriesController');
    Route::resource('/orders', 'Api\OrdersController');
    Route::resource('/vendor/orders', 'Api\VendorOrdersController');
   
    
    
});
  
  