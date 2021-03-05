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
    
    Route::resource('/products', 'Api\ProductController'); //these are the import products.. means vendors imported products..
    Route::get('/importproducts/count', 'Api\ProductController@count');// it returns the vendor total number of products..
    Route::get('/importproducts/search/{name}', 'Api\ProductController@search'); // it returns the searched products..
    Route::resource('/invoices','Api\InvoiceController'); //these are the invoices created on vendor side..
    Route::resource('/categories', 'Api\CategoriesController'); // these are the categories of vendor imported products..
  //  Route::resource('/orders', 'Api\OrdersController');  these orders are not necessary..
    Route::resource('/vendor/orders', 'Api\VendorOrdersController'); //these are the orders of vendors. means vendor orders. created by the end user of vendor.. after payment we will generate order to hareer..
    Route::get('/vendor/orders/cancel/{id}', 'Api\VendorOrdersController@cancel');
   
    
    
});

  
  
