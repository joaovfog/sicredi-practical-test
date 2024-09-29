<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('users', App\Http\Controllers\UserController::class);
Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users');
Route::post('/users', [App\Http\Controllers\UserController::class, 'createUser'])->name('users.createUser');
Route::delete('/users/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
Route::put('/users/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');

