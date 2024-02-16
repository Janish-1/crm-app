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

Route::get('/quiz/{category}/{testid}', [Quiz::class, 'openQuiz'])->name('quiz');
Route::get('/submit-quiz/{category}/{testid}', [Quiz::class, 'submitQuiz'])->name('submit-quiz');
Route::get('/pass/{category}/{testid}', [Quiz::class, 'passfunction']);
Route::get('/fail/{category}/{testid}', [Quiz::class, 'failfunction']);
Route::get('/error',[Quiz::class,'errorPage'])->name('error');

Route::get('/passpage',[Quiz::class,'passpage'])->name('passpage');
Route::get('/failpage',[Quiz::class,'failpage'])->name('failpage');