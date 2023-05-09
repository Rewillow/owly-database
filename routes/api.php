<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\SubjectsController;

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

// CORSI

Route::middleware(['throttle:api'])->group(function () {

Route::get('/courses', [CoursesController::class, 'coursesList']); // Per visualizzare tutti i corsi

Route::get('/courses/{course}', [CoursesController::class, 'showCourse']); // Per visualizzare il singolo corso

Route::post('/courses', [CoursesController::class, 'storeCourse']); // Per creare un nuovo corso

Route::put('/courses/{course}', [CoursesController::class, 'updateCourse']); // Per modificare un corso esistente

Route::delete('/courses/{course}', [CoursesController::class, 'destroy']); // Per eliminare un corso specifico

// MATERIE 

Route::get('/subjects', [SubjectsController::class, 'subjectsList']); // Per visualizzare tutte le materie

Route::get('/subjects/{subject}', [SubjectsController::class, 'showSubject']); // Per visualizzare la singola materia

Route::post('/subjects', [SubjectsController::class, 'storeSubject']); // Per creare un nuovo corso

Route::put('/subjects/{subject}', [SubjectsController::class, 'updateSubject']); // Per modificare una materia esistente

Route::delete('/subjects/{subject}', [SubjectsController::class, 'destroy']); // Per eliminare una materia specifica

});