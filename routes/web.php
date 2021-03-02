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

Route::get('/absensi/{tgl}', 'MahasiswaController@absensi');
Route::post('/absen', 'MahasiswaController@absen');
Route::get('/qr_code/index','QRController@index')->name('qrcode.index');
Route::get('/qr_code/check/{nrp}', 'QRController@check');
Route::post('/checkNrp', 'MahasiswaController@checkNrp');

Route::group(['prefix' => 'rmh'], function () {
    Route::get('/login', function () {
        return view('login');
    });
    Route::post('/login', 'MahasiswaController@login')->name('login');
    Route::get('/logout', 'MahasiswaController@logout')->name('logout');

    Route::group(['middleware' => ['auth']], function () {
        Route::get('/servant', 'MahasiswaController@servant');

        Route::get('/list_mhs', 'MahasiswaController@list_mhs');
        Route::get('/admin_absensi', 'MahasiswaController@admin_absensi');
        Route::post('/tambah_absensi', 'MahasiswaController@tambah_absensi');
        Route::post('/lihat_data_absensi', 'MahasiswaController@lihat_data_absensi');
        Route::get('/getJurusan', 'MahasiswaController@getJurusan');
        Route::get('/getEvents', 'MahasiswaController@getEvents');

        Route::group(['prefix' => 'mhs'], function () {
            Route::get('/delete/{nrp}', 'MahasiswaController@delete_mhs');
            Route::get('/getDataMhs/{nrp}', 'MahasiswaController@getDataMhs');
            Route::post('/edit', 'MahasiswaController@editMhs');
            Route::post('/delete', 'MahasiswaController@delete');
            Route::get('/konfirmasi/{nrp}/{value}', 'MahasiswaController@konfirmasi');
        });

        Route::group(['prefix' => 'absensi'], function () {
            Route::get('buka/{header_kehadiran:id_header_kehadiran}', 'MahasiswaController@buka');
            Route::get('tutup/{header_kehadiran:id_header_kehadiran}', 'MahasiswaController@tutup');
            Route::get('selesai/{header_kehadiran:id_header_kehadiran}', 'MahasiswaController@selesai');
            Route::get('detail/{id}', 'MahasiswaController@detail_absensi');
            Route::get('delete/{id}', 'MahasiswaController@delete_absensi');
            Route::get('getDataDetail/{id}', 'MahasiswaController@getDetailKehadiran');
        });

        Route::group(['prefix' => 'laporan'], function () {
            Route::get('/', 'MahasiswaController@laporan');
            Route::get('/getLaporan/{date_from}/{date_to}', 'MahasiswaController@getLaporanPerUser');
            Route::get('/exportToExcel/{date_from}/{date_to}', 'MahasiswaController@convertExcel');
        });

        Route::group(['prefix' => 'qr_code'], function () {
            Route::get('create','QRController@create')->name('qrcode.create');
            Route::get('generate/{nrp?}', 'QRController@generateQRCode');
        });
    });
});
