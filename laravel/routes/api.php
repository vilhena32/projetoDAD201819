<?php

use Illuminate\Http\Request;

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
//auth
Route::post('login', 'LoginControllerAPI@login')->name('login');
Route::post('register', 'AuthControllerAPI@register')->name('register');
Route::post('reset/password/{token}', 'AuthControllerAPI@resetPassword')->name('resetPassword');
Route::put('user/password/{id}', 'UsersControllerAPI@updatePassword');



#------ Passport
Route::post('login', 'LoginControllerAPI@login')->name('login');
Route::middleware('auth:api')->post('logout','LoginControllerAPI@logout');
Route::middleware('auth:api')->get('users/me', 'UsersControllerAPI@myProfile');



#------ Items (ementa)
Route::get('items', 'ItemsControllerAPI@index');
Route::post('items', 'ItemsControllerAPI@store');
Route::post('item_image', 'ItemsControllerAPI@getFileName');
Route::put('items/{id}', 'ItemsControllerAPI@update');
Route::delete('items/{id}','ItemsControllerAPI@destroy');
Route::get('allItems', 'ItemsControllerAPI@getAllItems');


#------ Users
Route::get('users', 'UsersControllerAPI@index');
Route::get('users/emailavailable', 'UsersControllerAPI@emailAvailable');
Route::get('user/{token}','UsersControllerAPI@getUserByToken');
Route::get('users/{id}', 'UsersControllerAPI@show');
Route::post('users', 'UsersControllerAPI@store');
Route::put('users/{id}', 'UsersControllerAPI@update');
Route::post('user_image', 'AuthControllerAPI@getFileName');

Route::delete('users/{id}', 'UsersControllerAPI@destroy');
Route::put('users/blocked/{id}', 'UsersControllerAPI@blocked');
Route::put('users/shift/{id}', 'UsersControllerAPI@activeShift');
Route::get('blockedUsers','UsersControllerAPI@getBlockedUsers');
Route::get('unblockedUsers','UsersControllerAPI@getUnblockedUsers');
#----- Orders
Route::get('orders', 'OrderControllerAPI@index');
Route::post('orders', 'OrderControllerAPI@store');
Route::delete('orders/{id}', 'OrderControllerAPI@destroy');
Route::get('userorders/{id}', 'OrderControllerAPI@getUserOrders');
Route::get('getpreparedorders/{id}', 'OrderControllerAPI@getPreparedOrders');
Route::put('takeorder/{id}', 'OrderControllerAPI@takeOrder');
Route::put('orderstate/{id}', 'OrderControllerAPI@changeState');

#----- Tables
Route::get('tables', 'TableControllerAPI@index');
Route::delete('tables/{id}', 'TableControllerAPI@destroy');
Route::post('tables', 'TableControllerAPI@store');


#----- Meals
Route::get('meals', 'MealControllerAPI@index');
Route::post('meals', 'MealControllerAPI@store');
Route::get('usermeals/{id}','MealControllerAPI@getUserMeals');
Route::get('getmeal/{id}', 'MealControllerAPI@getMeal');
Route::get('getmealitems/{id}', 'MealControllerAPI@getMealItems');
Route::put('terminatemeal/{id}', 'MealControllerAPI@terminateMeal');
Route::get('getmealorders/{id}', 'MealControllerAPI@getMealOrders');
Route::get('allMeals', 'MealControllerAPI@getAllMeals');

#----- Invocies
Route::post('invoices/{id}','InvoiceControllerAPI@create');
Route::get('invoices/pending','InvoiceControllerAPI@getPendingInvoices');
Route::get('invoices/paid','InvoiceControllerAPI@getPaidInvoices');
Route::get('invoices/notpaid','InvoiceControllerAPI@getNotPaidInvoices');
Route::put('invoices/{id}', 'InvoiceControllerAPI@update');
Route::put('closeInvoice/{id}','InvoiceControllerAPI@closeInvoice');

#---- Invoice items
Route::post('invoiceItems/{id}', 'InvoiceItemControllerAPI@store');
Route::get('invoiceItems/{id}','InvoiceItemControllerAPI@getInvoiceItems');
