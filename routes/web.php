<?php

use App\Http\Controllers\Dashboard;
use App\Http\Controllers\Home;
use Illuminate\Support\Facades\Route;


Route::get('/',[Home::class,'index']);
Route::get('/test',[Home::class, 'test']);
Route::post('/upload',[Home::class, 'upload'])->name('upload');
Route::get('/calculate',[Home::class, 'calculate'])->name('calculate');
//Route::get('/dashboard',[Dashboard::class, 'show']);