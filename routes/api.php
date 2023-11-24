
<?php

use App\Http\Controllers\NewClassController;
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
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EssaiController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('util', UtilisatorController::class);
Route::apiResource('teacher', TeacherController::class);
/*Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('teacher', TeacherController::class);
});*/
Route::apiResource('student', StudentController::class);
Route::apiResource('categ', CategoryController::class);
Route::apiResource('course', CourseController::class);
Route::apiResource('new', NewClassController::class);
//Route::put('new/{id}',[NewClassController::class,'update']);
//Route::post('new',[NewClassController::class,'store']);


Route::apiResource('essai',EssaiController::class);


Route::apiResource('admin', AdministratorController::class)->middleware('utilisator');
Route::apiResource('conferences', VisioconferenceController::class);
Route::get('/conferences/todo', [VisioconferenceController::class, 'getToDoConferences']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/refresh', [AuthController::class, 'refresh']);
Route::post('/send', [ContactController::class,'sendMessage'])->name('email');
// Exemple de route pour l'édition de la photo
Route::put('/utilisateur/{id}/editer-photo', 'UtilisateurController@editerPhoto');
