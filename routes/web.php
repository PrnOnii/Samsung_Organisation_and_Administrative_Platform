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

Route::get("/", "StudentController@index")->name('home');
Route::get("/student", "StudentController@index")->name('students');
Route::get("/student/add", "StudentController@create")->name('addStudent');
Route::post("/student/add", "StudentController@store");
Route::get("/student/addBulk", "StudentController@createBulk")->name('addStudentBulk');
Route::post("/student/addBulk", "StudentController@storeBulk");
Route::get("/student/{id}", "StudentController@show")->name('showStudent');

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



Route::get("/settings/2019", function () {
    if (!\App\PangSettings::first())
    \App\PangSettings::create([
        "current_promo_id" => 1,
    ]);
    return redirect("/");
});

//Route::get("/test", function () {

//});