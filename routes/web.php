<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get("/student", "StudentController@index");
Route::get("/student/add", "StudentController@create");
Route::post("/student/add", "StudentController@store");
Route::get("/student/addBulk", "StudentController@createBulk");
Route::post("/student/addBulk", "StudentController@storeBulk");
Route::get("/student/show", "StudentController@show");

Route::get("/promo/add", "PromoController@create");
Route::post("/promo/add", "PromoController@store");

