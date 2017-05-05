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

Route::group(['prefix' => 'watch', 'as' => 'api.watch.'], function() {
  //Route::get('/get-link/{url}', 'API\WatchController@getLink')->name('getlink');

  Route::get('/{film_id}', 'API\WatchController@watchFilm')
    ->where(['film_id' => '[0-9]+'])
    ->name('film');
  Route::get('/{film_id}/{episode_id}', 'API\WatchController@watchEpisode')
    ->where(['film_id' => '[0-9]+', 'episode_id' => '[0-9]+'])
    ->name('episode');
});

Route::group(['prefix' => 'search', 'as' => 'api.search.'], function() {
  Route::get('/films/{query}', 'API\SearchController@films')->name('film');
});
