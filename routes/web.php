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

Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware'=>['web', 'auth']], function (){
    Route::resource('logs', 'JobLogController')->only(['index', 'show', 'destroy']);
    Route::get('/logs/{type}/{id}', 'JobLogController@showGroup');
    Route::get('/jobs', 'JobController@index')->name('jobs.index');
    Route::get('/jobs/watch', 'JobController@watch')->name('jobs.watch');
    Route::get('/job/{job}/show', 'JobController@show')->name('jobs.show');
    Route::get('/job/{job}', 'JobController@create')->name('jobs.create');
    Route::post('/job/{job}', 'JobController@store');
});


