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

Route::resource('users', 'UsersController')->middleware('admin');


Route::get('/gocardless', 'GoCardLessController@index')->name('gocardless.index')->middleware('admin');
Route::get('/gocardless/{gocardless}/edit', 'GoCardLessController@edit')->name('gocardless.edit')->middleware('admin');
Route::put('/gocardless/{gocardless}', 'GoCardLessController@update')->name('gocardless.update')->middleware('admin');
Route::get('/gocardless/refresh', 'GoCardLessController@refresh')->name('gocardless.refresh')->middleware('admin');
Route::post('/gocardless/delete_tenancy/{gocardless}', 'GoCardLessController@deleteTenancy')->name('GoCardLess.deleteTenancy')->middleware('admin');

Route::get('/properties', 'PropertiesController@index')->name('properties.index')->middleware('admin');
Route::get('/properties/refresh', 'PropertiesController@refresh')->name('properties.refresh')->middleware('admin');

Route::get('/tenancies', 'TenanciesController@index')->name('tenancies.index')->middleware('admin');
Route::get('/tenancies/by/{date}', 'TenanciesController@byDate')->name('tenancies.byDate')->middleware('admin');
Route::get('/tenancies/{tenancy}/edit', 'TenanciesController@edit')->name('tenancies.edit')->middleware('admin');
Route::put('/tenancies/{tenancy}', 'TenanciesController@update')->name('tenancies.update')->middleware('admin');
Route::get('/tenancies/refresh', 'TenanciesController@refresh')->name('tenancies.refresh')->middleware('admin');

Route::get('/yodlee', 'YodleeTransactionsController@index')->name('yodlee.index')->middleware('admin');
Route::get('/yodlee/{yodlee}/edit', 'YodleeTransactionsController@edit')->name('yodlee.edit')->middleware('admin');
Route::put('/yodlee/{yodlee}', 'YodleeTransactionsController@update')->name('yodlee.update')->middleware('admin');
Route::get('/yodlee/refresh', 'YodleeTransactionsController@refresh')->name('yodlee.refresh')->middleware('admin');
Route::post('/yodlee/delete_tenancy/{yodlee}', 'YodleeTransactionsController@deleteTenancy')->name('Yodlee.deleteTenancy')->middleware('admin');

Route::get('/refresh', 'AdminController@index')->name('admin.refresh')->middleware('admin');
Route::get('/refresh/all', 'AdminController@refreshAll')->name('admin.refresh.all')->middleware('admin');