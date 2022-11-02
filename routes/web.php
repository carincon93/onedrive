<?php

use App\Helpers\OneDrive;
use App\Http\Controllers\ArchivoController;
use App\Http\Controllers\CarpetaController;
use App\Models\Carpeta;
use Illuminate\Http\Request;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function (Request $request) {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('archivos/{archivo}/download/', [ArchivoController::class, 'download'])->name('archivos.download');
Route::post('archivos/store/{carpeta}', [ArchivoController::class, 'store'])->name('archivos.store');
Route::delete('archivos/{archivo}/destroy', [ArchivoController::class, 'destroy'])->name('archivos.destroy');

Route::post('carpetas-hijas/{carpeta}/store', [CarpetaController::class, 'carpetasHijasStore'])->name('carpetas-hijas.store');
Route::resource('carpetas', CarpetaController::class)->parameters(['carpetas' => 'carpeta']);

require __DIR__ . '/auth.php';
