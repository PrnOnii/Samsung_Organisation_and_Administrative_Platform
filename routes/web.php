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

Route::get("/student", "StudentController@index")->name('students');
Route::get("/student/add", "StudentController@create")->name('addStudent');
Route::post("/student/add", "StudentController@store");
Route::get("/student/addBulk", "StudentController@createBulk")->name('addStudentBulk');
Route::post("/student/addBulk", "StudentController@storeBulk");
Route::get("/student/{id}", "StudentController@show")->name('showStudent');

Route::get("/promo/add", "PromoController@create")->name('addPromo');
Route::post("/promo/add", "PromoController@store");

// Check In / Out
Route::post("/student/checkIn", "StudentController@checkIn")->name("checkIn");
Route::post("/student/checkOut", "StudentController@checkOut")->name("checkOut");

Route::get("/settings/2019", function () {
    \App\PangSettings::create([
        "current_promo_id" => 1
    ]);
    return redirect("/student");
});