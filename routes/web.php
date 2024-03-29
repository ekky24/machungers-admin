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
    if (request()->session()->has('authenticated')) {
        return view('index');
     }
     else {
         return redirect('/login');
     }
});

Route::get('/firebase','FirebaseController@index');

Route::get('/berita/form','BeritaController@form');
Route::get('/berita','BeritaController@show_all');
Route::get('/berita/edit/{key}','BeritaController@edit');
Route::get('/berita/delete/{key}','BeritaController@delete');
Route::post('/berita','BeritaController@simpan');
Route::post('/berita/{key}','BeritaController@simpan_edit');

Route::get('/agenda/form','AgendaController@form');
Route::get('/agenda','AgendaController@show_all');
Route::get('/agenda/edit/{key}','AgendaController@edit');
Route::get('/agenda/delete/{key}','AgendaController@delete');
Route::post('/agenda','AgendaController@simpan');
Route::post('/agenda/{key}','AgendaController@simpan_edit');

Route::get('/fakultas/form','FakultasController@form');
Route::get('/fakultas','FakultasController@show_all');
Route::get('/fakultas/edit/{key}','FakultasController@edit');
Route::get('/fakultas/delete/{key}','FakultasController@delete');
Route::post('/fakultas','FakultasController@simpan');
Route::post('/fakultas/{key}','FakultasController@simpan_edit');

Route::get('/prodi/form','ProdiController@form');
Route::get('/prodi','ProdiController@show_all');
Route::get('/prodi/edit/{key}','ProdiController@edit');
Route::get('/prodi/delete/{key}','ProdiController@delete');
Route::post('/prodi','ProdiController@simpan');
Route::post('/prodi/{key}','ProdiController@simpan_edit');

Route::get('/faq/form','FaqController@form');
Route::get('/faq','FaqController@show_all');
Route::get('/faq/edit/{key}','FaqController@edit');
Route::get('/faq/delete/{key}','FaqController@delete');
Route::post('/faq','FaqController@simpan');
Route::post('/faq/{key}','FaqController@simpan_edit');

Route::get('/lifeatmachung/form','LifeAtMaChungController@form');
Route::get('/lifeatmachung','LifeAtMaChungController@show_all');
Route::get('/lifeatmachung/edit/{key}','LifeAtMaChungController@edit');
Route::get('/lifeatmachung/delete/{key}','LifeAtMaChungController@delete');
Route::get('/lifeatmachung_upload/delete/{key}','LifeAtMaChungController@delete_upload');
Route::post('/lifeatmachung','LifeAtMaChungController@simpan');
Route::post('/lifeatmachung_upload','LifeAtMaChungController@simpan_upload');
Route::post('/lifeatmachung/{key}','LifeAtMaChungController@simpan_edit');

Route::get('/user/form','UserController@form');
Route::get('/user','UserController@show_all');
Route::get('/user/edit/{key}','UserController@edit');
Route::get('/user/delete/{key}','UserController@delete');
Route::post('/user','UserController@simpan');
Route::post('/user/{key}','UserController@simpan_edit');

Route::get('/mahasiswa/form','MahasiswaController@form');
Route::get('/mahasiswa','MahasiswaController@show_all');
Route::get('/mahasiswa/edit/{key}','MahasiswaController@edit');
Route::get('/mahasiswa/edit/{key}/resetpass','MahasiswaController@resetpass');
Route::get('/mahasiswa/delete/{key}','MahasiswaController@delete');
Route::get('/mahasiswa_upload/delete/{key}','MahasiswaController@delete_upload');
Route::get('/mahasiswa/form_csv','MahasiswaController@form_csv');
Route::get('/mahasiswa/mulai_form_csv','MahasiswaController@simpan_csv');
Route::get('/mahasiswa/ubah_tgl_lahir','MahasiswaController@ubah_tgl_lahir');
Route::get('/ajax_mahasiswa_fakultas','MahasiswaController@ajax_fakultas');
Route::post('/mahasiswa','MahasiswaController@simpan');
Route::post('/mahasiswa_upload','MahasiswaController@simpan_upload');
Route::post('/mahasiswa/{key}','MahasiswaController@simpan_edit');

Route::get('/push_all/form','NotificationController@form_push_all');
Route::get('/push_fakultas/form','NotificationController@form_push_fakultas');
Route::get('/push_prodi/form','NotificationController@form_push_prodi');
Route::get('/push_individu/form','NotificationController@form_push_individu');
Route::get('/push','NotificationController@show_all');
Route::post('/push_all','NotificationController@simpan');
Route::post('/push_fakultas','NotificationController@simpan');
Route::post('/push_prodi','NotificationController@simpan');
Route::post('/push_individu','NotificationController@simpan');

Route::get('/newsletter/form','NewsletterController@form');
Route::get('/newsletter','NewsletterController@show_all');
Route::get('/newsletter/delete/{key}','NewsletterController@delete');
Route::post('/newsletter','NewsletterController@simpan');

Route::get('/login', 'SessionController@create');
Route::get('/logout', 'SessionController@logout');
Route::get('/setting/form', 'SessionController@setting');
Route::post('/session', 'SessionController@store');
Route::post('/setting/{key}', 'SessionController@simpan_setting');
Route::post('login', [ 'as' => 'login', 'uses' => 'SessionController@create']);

Route::get('/storage/{path}/{filename}', 'SessionController@storage');