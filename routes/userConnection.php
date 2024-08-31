<?php

use App\Http\Controllers\NetworkController;
use Illuminate\Support\Facades\Route;


// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/home', [NetworkController::class, 'index'])->name('index')->middleware('auth');
Route::post('/create', [NetworkController::class, 'Store'])->name('create.connect.request')->middleware('auth');
Route::post('/update', [NetworkController::class, 'update'])->name('accept.connect.request')->middleware('auth');
Route::post('/withraw', [NetworkController::class, 'withraw'])->name('connect.request.withraw')->middleware('auth');
Route::post('/destroy', [NetworkController::class, 'destroy'])->name('connect.request.destroy')->middleware('auth');


Route::get('/network/load-more', [NetworkController::class, 'loadMore'])->name('network.loadMore');
