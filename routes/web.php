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

Route::get('/agenda/form','AgendaController@form');

Route::get('/fakultas/form','FakultasController@form');

Route::get('/prodi/form','ProdiController@form');

Route::get('/faq/form','FaqController@form');

Route::get('/lifeatmachung/form','LifeAtMaChungController@form');

Route::get('/user/form','UserController@form');

Route::get('/mahasiswa/form','MahasiswaController@form');