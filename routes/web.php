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
    return view('index');
});

Route::get('/firebase','FirebaseController@index');

Route::get('/berita/form','BeritaController@form');
Route::get('/berita','BeritaController@show_all');
Route::get('/berita/edit/{key}','BeritaController@edit');
Route::get('//berita/delete/{key}','BeritaController@delete');
Route::post('/berita','BeritaController@simpan');
Route::post('/berita/{key}','BeritaController@simpan_edit');

Route::get('/agenda/form','AgendaController@form');
Route::get('/agenda','AgendaController@show_all');
Route::post('/agenda','AgendaController@simpan');

Route::get('/fakultas/form','FakultasController@form');
Route::get('/fakultas','FakultasController@show_all');
Route::post('/fakultas','FakultasController@simpan');

Route::get('/prodi/form','ProdiController@form');

Route::get('/faq/form','FaqController@form');

Route::get('/lifeatmachung/form','LifeAtMaChungController@form');

Route::get('/user/form','UserController@form');

Route::get('/mahasiswa/form','MahasiswaController@form');