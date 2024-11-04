<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

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

Auth::routes();

// Route::get('/home', [App\Http\Controllers\TaskController::class, 'index'])->name('home');
Route::get('/home', [TaskController::class, 'index'])->middleware('auth')->name('home');

Route::get('/trashed', [TaskController::class, 'trashed'])->middleware('auth')->name('tasks.trashed');


Route::get('/edit/{task}', [TaskController::class, 'edit'])->middleware('auth')->name('tasks.edit');
Route::put('/update/{task}', [TaskController::class, 'update'])->middleware('auth')->name('tasks.update');


Route::delete('/delete/{task}', [TaskController::class, 'destroy'])->middleware('auth')->name('tasks.destroy');

Route::get('/create', [TaskController::class, 'create'])->middleware('auth')->name('tasks.create');
Route::post('/store', [TaskController::class, 'store'])->middleware('auth')->name('tasks.store');


Route::put('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');

Route::put('/tasks/{id}/restore', [TaskController::class, 'restore'])->name('tasks.restore');
Route::delete('/forceDelete/{id}', [TaskController::class, 'forceDelete'])->middleware('auth')->name('tasks.forceDelete');

