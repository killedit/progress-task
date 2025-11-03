<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Password;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API is running'
    ]);
});

Route::get('/tasks', [TaskController::class, 'index']);// ! PUBLIC - but not for editing

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
    Route::patch('/tasks/{task}/complete', [TaskController::class, 'complete']);
});

Route::middleware('auth:sanctum')->get('/users', function() {

    // dd("API /users route called");

    return \App\Models\User::all();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? response()->json(['message' => __($status)])
        : response()->json(['message' => __($status)], 400);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
