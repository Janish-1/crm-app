<?php

use App\Http\Controllers\Quiz;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/quiz/{category}', [Quiz::class, 'openQuiz'])->name('open-quiz');
Route::post('/submit-quiz/{category}', [Quiz::class, 'submitQuiz'])->name('submit-quiz');
Route::get('/pass/{category}', [Quiz::class, 'passPage'])->name('pass');
Route::get('/fail/{category}', [Quiz::class, 'failPage'])->name('fail');