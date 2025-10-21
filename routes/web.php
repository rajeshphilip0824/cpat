<?php

use App\Http\Controllers\Dashboard;
use App\Http\Controllers\Home;
use Illuminate\Support\Facades\Route;


Route::get('/',[Home::class,'index']);
Route::get('/test',[Home::class, 'test']);
Route::get('/dashboard',[Dashboard::class, 'show']);