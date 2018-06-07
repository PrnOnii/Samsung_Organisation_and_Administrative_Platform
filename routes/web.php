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

Auth::routes();

Route::get("/", "HomeController@index");
Route::get("/home", "HomeController@index")->name('home');
Route::get("/student", "StudentController@index")->name('students');

// Students
Route::get("/student/add", "StudentController@create")->name('addStudent');
Route::post("/student/add", "StudentController@store");
Route::get("/student/addBulk", "StudentController@createBulk")->name('addStudentBulk');
Route::post("/student/addBulk", "StudentController@storeBulk");
Route::get("/student/{id}", "StudentController@show")->name('showStudent');
Route::get("/student/{id}/edit", "StudentController@edit")->name("editStudent");
Route::post("/student/{id}/edit", "StudentController@update");
Route::delete("/student/{id}", "StudentController@destroy")->name("deleteStudent");

Route::get("/promo/add", "PromoController@create")->name('addPromo');
Route::post("/promo/add", "PromoController@store");

// Check In / Out
Route::post("/student/checkIn", "DayController@checkIn")->name("checkIn");
Route::post("/student/checkOut", "DayController@checkOut")->name("checkOut");

// Administrative
Route::get("/justify", "DayController@justify")->name("justify");
Route::post("/justify", "DayController@storeJustify");
Route::get("/editChecks", "DayController@editChecks")->name("editChecks");
Route::post("/editChecks", "DayController@updateChecks");
Route::get("/editPangs", "DayController@editPangs")->name("editPangs");
Route::post("/editPangs", "DayController@updatePangs");
