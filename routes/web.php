<?php

use App\Http\Controllers\LoginController;
use App\Models\Employee;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

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
    $jumlahpegawai = Employee::count();
    $jumlahpegawaicowok = Employee::where('jeniskelamin', 'cowok')->count();
    $jumlahpegawaicewek = Employee::where('jeniskelamin', 'cewek')->count();
    return view('welcome', compact('jumlahpegawai', 'jumlahpegawaicowok', 'jumlahpegawaicewek'));
})->middleware('auth');


Route::get('/pegawai',[EmployeeController::class, 'index'])->name('pegawai')->middleware('auth');

Route::group(['middleware' => ['auth', 'hakakses:admin']], function(){
Route::get('/tambahpegawai',[EmployeeController::class, 'tambahpegawai'])->name('tambahpegawai');
Route::post('/importexcel',[EmployeeController::class, 'importexcel'])->name('importexcel');
Route::get('/delete/{id}',[EmployeeController::class, 'delete'])->name('delete');
Route::get('/tampilkandata/{id}',[EmployeeController::class, 'tampilkandata'])->name('tampilkandata');

});

Route::post('/insertdata',[EmployeeController::class, 'insertdata'])->name('insertdata');

Route::post('/updatedata/{id}',[EmployeeController::class, 'updatedata'])->name('updatedata');

//export pdf
Route::get('/exportpdf',[EmployeeController::class, 'exportpdf'])->name('exportpdf');
//export excel
Route::get('/exportexcel',[EmployeeController::class, 'exportexcel'])->name('exportexcel');





Route::get('/login',[LoginController::class, 'login'])->name('login');
Route::post('/loginproses',[LoginController::class, 'loginproses'])->name('loginproses');

Route::get('/register',[LoginController::class, 'register'])->name('register');
Route::post('/registeruser',[LoginController::class, 'registeruser'])->name('registeruser');

Route::get('/logout',[LoginController::class, 'logout'])->name('logout');



