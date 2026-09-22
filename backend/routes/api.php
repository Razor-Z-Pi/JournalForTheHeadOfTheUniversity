<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\DisciplineController;
use App\Http\Controllers\Api\JournalController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\SubgroupController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('students', StudentController::class);
    Route::apiResource('disciplines', DisciplineController::class);
    Route::get('/journal/{disciplineId}', [JournalController::class, 'index']);
    Route::post('/journal/update', [JournalController::class, 'updateCell']);
    Route::apiResource('groups', GroupController::class);
    Route::apiResource('subgroups', SubgroupController::class);
});