<?php

use Illuminate\Support\Facades\Route;

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

Route::redirect('/', 'contacts');

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

Route::get('contacts', 'ContactController@index');
Route::get('/files', 'ContactController@files')->name('files');
Route::get('/download_file/{file}', 'ContactController@downloadFile');
Route::post('/import_parse', 'ContactController@parseImport')->name('import_parse');
Route::post('/import_process', 'ContactController@processImport')->name('import_process');