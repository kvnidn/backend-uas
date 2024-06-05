<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', [
        "title"=> "Home",
    ]);
});

Route::get('/about', function () {
    return view('about', [
        "title" => "About",
    ]);
});

Route::get('/admin', function () {
    return view('admin', [
        "title" => "Admin",
    ]);
});


Route::get('/user', [UserController::class, 'index']);
Route::get('/user/create', [UserController::class, 'create']);
Route::post('/user/create', [UserController::class, 'store']);
Route::get('/user/{id}/edit', [UserController::class, 'edit']);
Route::put('/user/{id}/edit', [UserController::class, 'update']);
Route::get('/user/{id}/delete', [UserController::class, 'destroy']);


Route::get('/test', function () {
    return view('welcome', [
        "title" => "Admin",
    ]);
});
