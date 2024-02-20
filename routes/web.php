<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
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
    return view('login');
});

//public route
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');
Route::post('/auth/register', [AuthController::class, 'register'])->name('register');
Route::get('/registerpage',[AuthController::class,'registerpage'])->name('registerpage');

//protected route
// Route::group(['middleware' => ['auth:sanctum']], function () {
//     Route::get('/home', [AuthController::class, 'home'])->name('home');
//     Route::get('/users', [PostController::class, 'show'])->middleware('restrictRole:admin')->name('users');
//     Route::put('/users/{id}', [PostController::class, 'update'])->middleware('restrictRole:moderator');
// });

Route::get('/home', [AuthController::class, 'home'])->name('home');
Route::get('/users', [PostController::class, 'show'])->middleware('restrictRole:admin')->name('users');
Route::put('/users/{id}', [PostController::class, 'update'])->middleware('restrictRole:moderator');

Route::get('/quiz/{category}/{testid}', [Quiz::class, 'openQuiz'])->name('quiz');
Route::get('/submit-quiz/{category}/{testid}', [Quiz::class, 'submitQuiz'])->name('submit-quiz');
Route::get('/pass/{category}/{testid}', [Quiz::class, 'passfunction']);
Route::get('/fail/{category}/{testid}', [Quiz::class, 'failfunction']);
Route::get('/error', [Quiz::class, 'errorPage'])->name('error');

Route::get('/passpage', [Quiz::class, 'passpage'])->name('passpage');
Route::get('/failpage', [Quiz::class, 'failpage'])->name('failpage');