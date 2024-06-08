<?php

use App\Http\Controllers\ScheduleController;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AssignmentController;

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

Route::get('/user', [UserController::class, 'index'])->middleware('auth');
Route::get('/user/create', [UserController::class, 'create'])->middleware('auth');
Route::post('/user/create', [UserController::class, 'store']);
Route::get('/user/{id}/edit', [UserController::class, 'edit'])->middleware('auth');
Route::put('/user/{id}/edit', [UserController::class, 'update']);
Route::get('/user/{id}/delete', [UserController::class, 'destroy'])->middleware('auth');

Route::get('/room', [RoomController::class, 'index'])->middleware('auth');
Route::get('/room/create', [RoomController::class, 'create'])->middleware('auth');
Route::post('/room/create', [RoomController::class, 'store']);
Route::get('/room/{id}/edit', [RoomController::class, 'edit'])->middleware('auth');
Route::put('/room/{id}/edit', [RoomController::class, 'update']);
Route::get('/room/{id}/delete', [RoomController::class, 'destroy'])->middleware('auth');

Route::get('/subject', [SubjectController::class, 'index'])->middleware('auth');
Route::get('/subject/create', [SubjectController::class, 'create'])->middleware('auth');
Route::post('/subject/create', [SubjectController::class, 'store']);
Route::get('/subject/{id}/edit', [SubjectController::class, 'edit'])->middleware('auth');
Route::put('/subject/{id}/edit', [SubjectController::class, 'update']);
Route::get('/subject/{id}/delete', [SubjectController::class, 'destroy'])->middleware('auth');

Route::get('/assignment', [AssignmentController::class, 'index'])->middleware('auth');
Route::get('/assignment/create', [AssignmentController::class, 'create'])->middleware('auth');
Route::post('/assignment/create', [AssignmentController::class, 'store']);
Route::get('/assignment/{id}/edit', [AssignmentController::class, 'edit'])->middleware('auth');
Route::put('/assignment/{id}/edit', [AssignmentController::class, 'update']);
Route::get('/assignment/{id}/delete', [AssignmentController::class, 'destroy'])->middleware('auth');

Route::get('/schedule', [ScheduleController::class, 'index'])->middleware('auth');
Route::get('/schedule/create', [ScheduleController::class, 'create'])->middleware('auth');
Route::post('/schedule/create', [ScheduleController::class, 'store']);
Route::get('/schedule/{id}/edit', [ScheduleController::class, 'edit'])->middleware('auth');
Route::put('/schedule/{id}/edit', [ScheduleController::class, 'update']);
Route::get('/schedule/{id}/delete', [ScheduleController::class, 'destroy'])->middleware('auth');


Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authentication']);
Route::post('/logout', [LoginController::class, 'logout']);

Route::get('/create-user', [UserController::class,'createUser']);


Route::get('/test', function () {
    return view('welcome', [
        "title" => "Admin",
    ]);
});
