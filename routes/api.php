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


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('util',UtilisatorController::class);
Route::apiResource('teacher',TeacherController::class);
Route::apiResource('student',StudentController::class);
Route::apiResource('categ',CategoryController::class);
Route::apiResource('course',CourseController::class);
Route::apiResource('admin',AdministratorController::class);
Route::apiResource('conferences',VisioconferenceController::class);
Route::get('/conferences/todo', [VisioconferenceController::class, 'getToDoConferences']);
