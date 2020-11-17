<?php

use App\Http\Controllers\ReviewController;
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

Route::get('/', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('/create', [ReviewController::class, 'create'])->name('reviews.create');
Route::post('/update', [ReviewController::class, 'store'])->name('reviews.store');
Route::get('/show/{review}', [ReviewController::class, 'show'])->name('reviews.show');
Route::post('/like', [ReviewController::class, 'like'])->name('reviews.like');
