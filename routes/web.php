<?php

use App\Http\Controllers\KeyLendingController;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AssignmentController;

Route::view('/', 'home', ["title" => "Home"]);
Route::view('/about', 'about', ["title" => "About"]);

// User
Route::prefix('user')->middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/create', [UserController::class, 'create']);
    Route::post('/create', [UserController::class, 'store']);
    Route::get('{id}/edit', [UserController::class, 'edit']);
    Route::put('{id}/edit', [UserController::class, 'update']);
    Route::get('{id}/delete', [UserController::class, 'destroy']);
});

// Room
Route::prefix('room')->middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/', [RoomController::class, 'index']);
    Route::get('/create', [RoomController::class, 'create']);
    Route::post('/create', [RoomController::class, 'store']);
    Route::get('{id}/edit', [RoomController::class, 'edit']);
    Route::put('{id}/edit', [RoomController::class, 'update']);
    Route::get('{id}/delete', [RoomController::class, 'destroy']);
});

// Subject
Route::prefix('subject')->middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/', [SubjectController::class, 'index']);
    Route::get('/create', [SubjectController::class, 'create']);
    Route::post('/create', [SubjectController::class, 'store']);
    Route::get('{id}/edit', [SubjectController::class, 'edit']);
    Route::put('{id}/edit', [SubjectController::class, 'update']);
    Route::get('{id}/delete', [SubjectController::class, 'destroy']);
});

// Assignment
Route::prefix('assignment')->middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/', [AssignmentController::class, 'index']);
    Route::get('/create', [AssignmentController::class, 'create']);
    Route::post('/create', [AssignmentController::class, 'store']);
    Route::get('{id}/edit', [AssignmentController::class, 'edit']);
    Route::put('{id}/edit', [AssignmentController::class, 'update']);
    Route::get('{id}/delete', [AssignmentController::class, 'destroy']);
});

// Schedule Admin
Route::prefix('schedule')->middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/create', [ScheduleController::class, 'create']);
    Route::post('/create', [ScheduleController::class, 'store']);
    Route::get('{id}/delete', [ScheduleController::class, 'destroy']);
    Route::get('/batch-delete', [ScheduleController::class, 'batchDestroy']);
});

// Schedule Admin & Lecturer
Route::prefix('schedule')->middleware(['auth', 'role:Admin,Lecturer'])->group(function () {
    Route::get('/', [ScheduleController::class, 'index']);
    Route::get('{id}/edit', [ScheduleController::class, 'edit']);
    Route::put('{id}/update', [ScheduleController::class, 'update']);
    Route::get('/batch-edit', [ScheduleController::class, 'batchEdit']);
    Route::put('/batch-update', [ScheduleController::class, 'batchUpdate']);
});

Route::get('/view/{date?}', [ScheduleController::class, 'view'])->name('schedule.view');

// Key Lending
Route::prefix('key-lending')->group(function () {
    Route::get('/', [KeyLendingController::class, 'viewToday'])->name('key-lending.viewToday');
    Route::get('{id}/verify', [KeyLendingController::class, 'showVerifyForm'])->name('key-lending.verify');
    Route::get('{id}/verify-end', [KeyLendingController::class, 'showEndVerifyForm'])->name('key-lending.verify-end');
    Route::post('{id}/verify-update-start', [KeyLendingController::class, 'verifyAndUpdateStart'])->name('key-lending.verify.update.start');
    Route::post('{id}/verify-update-end', [KeyLendingController::class, 'verifyAndUpdateEnd'])->name('key-lending.verify.update.end');
});

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authentication']);
});
Route::post('/logout', [LoginController::class, 'logout']);

// Class
Route::prefix('class')->middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/', [KelasController::class, 'index']);
    Route::get('/create', [KelasController::class, 'create']);
    Route::post('/create', [KelasController::class, 'store']);
    Route::get('{id}/edit', [KelasController::class, 'edit']);
    Route::put('{id}/edit', [KelasController::class, 'update']);
    Route::get('{id}/delete', [KelasController::class, 'destroy']);
});

// Route::get('/key-lending', [KeyLendingController::class, 'index'])->middleware('auth')->name('key-lending.index');
// Route::get('/key-lending/create', [KeyLendingController::class, 'create'])->middleware('auth');
// Route::post('/key-lending/create', [KeyLendingController::class, 'store']);
// Route::get('/key-lending/{id}/edit', [KeyLendingController::class, 'edit'])->middleware('auth');
// Route::put('/key-lending/{id}/edit', [KeyLendingController::class, 'update']);
// Route::get('/key-lending/{id}/delete', [KeyLendingController::class, 'destroy'])->middleware('auth');

Route::get('/create-user', [UserController::class,'createUser']);

