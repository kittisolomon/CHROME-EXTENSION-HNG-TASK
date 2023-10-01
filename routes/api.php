<?php

use App\Http\Controllers\RecordingsController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/', [RecordingsController::class, 'index']);
Route::post('/save_video', [RecordingsController::class, 'store']);
Route::get('/{id}', [RecordingsController::class, 'show']);
Route::delete('/{id}', [RecordingsController::class, 'destroy']);


