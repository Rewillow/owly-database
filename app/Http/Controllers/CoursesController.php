<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class CoursesController extends Controller
{
    public function coursesList() {
        $courses = Course::query()->with('subjects')
            ->when(request()->has('title'), function($query) {
                return $query->where('title', 'like', '%'.request('title'). '%');
            })
            ->when(request()->has('spots'), function($query) {
                return $query->where('spots', 'like', '%'.request('spots'). '%');
            })
            ->when(request()->has('subject_ids'), function($query) {
                $subject_ids = (array) request('subject_ids');
                return $query->whereHas('subjects', function($query) use ($subject_ids) {
                    $query->whereIn('subjects.id', $subject_ids);
                });
            })
            ->get();
    
        return response()->json($courses);
    }

    public function showCourse($id) {
        $course = Course::with('subjects')->findOrFail($id); // Mostra i singoli corsi con le rispettive materie
        return response()->json($course); // Restituisce i risultati in formato json
    }

    public function storeCourse(Request $request) {

        $data = $request->json()->all();
    
        $course = new Course([
            'title' => $data['title'],
            'spots' => $data['spots'],
        ]);
    
        $course->save(); // In questo modo salva il corso per assegnare il rispettivo ID
    
        $subjectIds = $request->input('subjects'); // Aggiunge le materie che abbia selezionato
        $course->subjects()->attach($subjectIds);
    
        return response()->json(['message' => 'Course create', 'course' => $course]); // Ritorna un messaggio di riuscita del processo
    }

    public function updateCourse(Request $request):Response {
      
        $course = Course::find($request->id);

        if ($course === null) {
            return response(
                "Course with id {$request->id} not found",
                Response::HTTP_NOT_FOUND
            );
        }

        if ($course->update($request->all()) === false) {
            return response(
                "Couldn't update the course with id {$request->id}",
                Response::HTTP_BAD_REQUEST
            );
        }

        return response($course);
    }


    public function destroy(Course $course) {
        $course->delete(); // Elimina un corso
        return response()->json(['message' => 'Course delete']); // Restituisce un messaggio di riuscita
    }
    
}
