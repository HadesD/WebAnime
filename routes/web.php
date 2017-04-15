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

Route::get('/home', 'HomeController@index');

Route::group(['prefix' => 'watch'], function() {
  Route::get('/', 'WatchController@index')->name('watch');

  Route::group(['prefix' => '{id}'], function($id) {
    Route::get('/', function() use ($id) {
      print_r($id);
      return 1;
    });
  });
});
