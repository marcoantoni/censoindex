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


Route::resource('search', 'SearchController');

Route::get('/', function () {
    return view('search');
});

Route::get('/stats/{idState}/{idCity?}', 'StatisticsController@getStatistics');

Route::resource('school', 'SchoolController')->only([
    'show'
]);

Route::resource('log', 'LogController')->only([
    'store'
]);
Route::post('/log/storerightanswer', 'LogController@storeRightAnswer');

Route::get('/about', function () {
    return view('about');
});
Route::get('/datasource', function () {
    return view('datasource');
});