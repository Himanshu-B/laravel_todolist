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

use App\Http\Controllers\TaskController;

Route::get('/', [TaskController::class, 'index']);
Route::post('/alltask', [TaskController::class, 'showAll']);
Route::post('/task', [TaskController::class, 'store']);
Route::get('/task/{id}/done', [TaskController::class, 'update']);
Route::get('/task/{id}/delete', [TaskController::class, 'destroy']);

// Route::get('/', function () {
//     return view('welcome');
// });
