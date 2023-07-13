<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UtilisatorController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\VisioconferenceController;
use App\Http\Controllers\Api\AuthController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('util',UtilisatorController::class);
Route::apiResource('teacher',TeacherController::class);
Route::apiResource('student',StudentController::class);
Route::apiResource('categ', CategoryController::class);
Route::apiResource('course',CourseController::class);
Route::apiResource('admin',AdministratorController::class);
Route::apiResource('conferences',VisioconferenceController::class);
Route::get('/conferences/todo', [VisioconferenceController::class, 'getToDoConferences']);
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});
