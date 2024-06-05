<?php

use App\Http\Controllers\RoomController;
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

Route::get('/room', function () {
    return view('room', [
        "title" => "Room",
    ]);
});


Route::get('/user', [UserController::class, 'index']);
Route::get('/user/create', [UserController::class, 'create']);
Route::post('/user/create', [UserController::class, 'store']);
Route::get('/user/{id}/edit', [UserController::class, 'edit']);
Route::put('/user/{id}/edit', [UserController::class, 'update']);
Route::get('/user/{id}/delete', [UserController::class, 'destroy']);

Route::get('/room', [RoomController::class, 'index']);
Route::get('/room/create', [RoomController::class, 'create']);
Route::post('/room/create', [RoomController::class, 'store']);
Route::get('/room/{id}/edit', [RoomController::class, 'edit']);
Route::put('/room/{id}/edit', [RoomController::class, 'update']);
Route::get('/room/{id}/delete', [RoomController::class, 'destroy']);

Route::get('/test', function () {
    return view('welcome', [
        "title" => "Admin",
    ]);
});
