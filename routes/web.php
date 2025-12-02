<?php

use App\Http\Controllers\Dashboard;
use App\Http\Controllers\Home;
use Illuminate\Support\Facades\Route;


Route::get('/',[Home::class,'index']);
Route::get('/component/{id}',[Home::class,'component'])->name('component');

Route::get('/test',[Home::class, 'test']);
Route::post('/upload',[Home::class, 'upload'])->name('upload');
Route::post('/uploadPCFile',[Home::class, 'uploadPCFile'])->middleware('web')->name('uploadPCFile');
Route::get('/calculate',[Home::class, 'calculate'])->name('calculate');



Route::get('/test1',[Home::class, 'test1']);
Route::get('/calculateTest',[Home::class, 'calculateTest'])->name('calculateTest');
//Route::get('/dashboard',[Dashboard::class, 'show']);