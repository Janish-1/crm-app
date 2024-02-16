<?php

use App\Http\Controllers\CareerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// Create a career
Route::post('/createcareer', [CareerController::class, 'createCareer']);

// Read a specific career
Route::get('/careers/{id}', [CareerController::class, 'readCareer']);

// Update a specific career
Route::put('/careers/{id}', [CareerController::class, 'updateCareer']);

// Delete a specific career
Route::delete('/careers/{id}', [CareerController::class, 'deleteCareer']);

// Read all careers
Route::get('/careers', [CareerController::class, 'readAllCareers']);

Route::post('/redirecttest',[CareerController::class,'redirectToTest']);