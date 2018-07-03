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

Route::get('login/live', 'Auth\LoginController@redirectToProvider');
Route::get('/authorize', 'Auth\LoginController@handleProviderCallback');

Route::get('/notallowed', 'HomeController@notAllowed')->name('notAllowed');

Route::get("/", "Auth\LoginController@showLoginForm");
Route::get("/home", "HomeController@index")->name('home');
Route::get("/student", "StudentController@index")->name('students');

// Students
Route::get("/student/add", "StudentController@create")->name('addStudent');
Route::post("/student/add", "StudentController@store");
Route::get("/student/addBulk", "StudentController@createBulk")->name('addStudentBulk');
Route::post("/student/addBulk", "StudentController@storeBulk");
Route::get("/student/{login}", "StudentController@show")->name('showStudent');
Route::get("/student/{login}/edit", "StudentController@edit")->name("editStudent");
Route::post("/student/{login}/edit", "StudentController@update");
Route::delete("/student/{login}", "StudentController@destroy")->name("deleteStudent");

Route::get("/promo/add", "PromoController@create")->name('addPromo');
Route::post("/promo/add", "PromoController@store");

// Check In / Out
Route::post("/student/checkIn", "DayController@checkIn")->name("checkIn");
Route::post("/student/checkOut", "DayController@checkOut")->name("checkOut");
Route::get("/json", "StudentController@jsonStudentsData");

// Administrative

Route::get("/logs", "DayController@logs")->name("logs");

// Justification
Route::get("/justify", "DayController@justify")->name("justify");
Route::post("/justify", "DayController@storeJustify");
Route::get("/editJustify", "DayController@editJustify")->name("editJustify");
Route::post("/editJustify", "DayController@updateJustify");
Route::post("/deleteJustify/{id}", "DayController@deleteJustify")->name("deleteJustify");

// Checks
Route::get("/editChecks", "DayController@editChecks")->name("editChecks");
Route::post("/editChecks", "DayController@updateChecks");

// Edit Pangs
Route::get("/editPangs", "DayController@editPangs")->name("editPangs");
Route::post("/editPangs", "DayController@updatePangs");
Route::post("/deletePangs/{id}", "DayController@deleteEditPangs")->name("deletePangs");
