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

Route::group(['prefix' => 'watch', 'as' => 'watch.'], function() {
  Route::get('/', 'WatchController@index')->name('index');

  Route::get('/{film_id}/{film_slug?}', 'WatchController@watchFilm')->name('film');

  Route::get('/{film_id}/{episode_id}/{film_slug?}/{episode_slug?}', 'WatchController@watchEpisode')->name('episode');

  // Route::group(['prefix' => '{film_id}'], function($film_id) {
  //   Route::get('/', function() use ($film_id) {
  //     print_r($film_id);
  //     return 1;
  //   });
  //
  //   Route::get('/{eposide_id}', function($eposide_id) use ($film_id) {
  //     print_r($id);
  //     return 1;
  //   });
  // });
});
