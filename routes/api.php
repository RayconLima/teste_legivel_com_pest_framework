<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/bugs', [App\Http\Controllers\BugController::class, 'index'])->name('bugs.index');
Route::post('/bugs', [App\Http\Controllers\BugController::class, 'store'])->name('bugs.store');
Route::put('/bugs/{id}', [App\Http\Controllers\BugController::class, 'update'])->name('bugs.update');
Route::delete('/bugs/{id}', [App\Http\Controllers\BugController::class, 'destroy'])->name('bugs.destroy');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
