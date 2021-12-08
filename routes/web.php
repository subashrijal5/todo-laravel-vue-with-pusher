<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes(['verify' => true]);


Route::prefix('todos')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [TaskController::class, 'index'])->name('todos.index');
    Route::get('/FetchData', [TaskController::class, 'FetchData'])->name('todos.FetchData');
    Route::post('/', [TaskController::class, 'store'])->name('todos.store');
    Route::post('/update', [TaskController::class, 'update'])->name('todos.update');
    Route::delete('/{id}', [TaskController::class, 'delete'])->name('todos.delete');
});
Route::prefix('comment')->group(function () {
    Route::post('/', [CommentController::class, 'store']);
});
