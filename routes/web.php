<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\IndexController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PageController::class, 'showForm'])->name('upload-xml');
Route::POST('/', [PageController::class, 'downloadAndProcessXml'])->name('upload-xml');
Route::get('/download', [PageController::class, 'exportToExcel'])->name('download-xlsx');

