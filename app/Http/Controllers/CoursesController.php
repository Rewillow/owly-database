<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class CoursesController extends Controller
{
    public function coursesList() { // Questa funzione restituisce tutti i corsi presenti e permette anche il filtraggio
        $courses = Course::query()->with('subjects') // In questo caso stiamo richiamando tutti i corsi e le rispettive materie
            ->when(request()->has('title'), function($query) { // Qui stabiliamo il filtraggio delle API tramite 'title'
                return $query->where('title', 'like', '%'.request('title'). '%');
            })
            ->when(request()->has('spots'), function($query) { // Qui stabiliamo il filtraggio delle API tramite 'spots'
                return $query->where('spots', 'like', '%'.request('spots'). '%');
            })
            ->when(request()->has('subject_ids'), function($query) { // Qui stabiliamo il filtraggio delle API tramite 'subject'
                $subject_ids = (array) request('subject_ids');
                return $query->whereHas('subjects', function($query) use ($subject_ids) {
                    $query->whereIn('subjects.id', $subject_ids);
                });
            })
            ->get();
    
        return response()->json($courses); // Tutto viene ritornato in formato "json"
    }

    public function showCourse($id) {
        $course = Course::with('subjects')->findOrFail($id); // Mostra i singoli corsi con le rispettive materie
        return response()->json($course); // Tutto viene ritornato in formato "json"
    }

    public function storeCourse(Request $request) { // Questa funzione permette la creazione di un nuovo corso 

        $data = $request->json()->all(); // Tramite questa variabile si fa una richiesta HTTP che ritorna tutti i parametri richiesti in formato json
    
        $course = new Course([ // Tramite questa variabile, dove viene richiamata la classe "Course" stabiliamo quali dati vogliamo creare
            'title' => $data['title'],
            'spots' => $data['spots'],
        ]);
    
        $course->save(); // In questo modo viene salvato il corso cosi da assegnare il rispettivo ID
    
        $subjectIds = $request->input('subjects'); // Aggiunge le materie che sono state selezionate
        $course->subjects()->attach($subjectIds); // In questo caso viene richiamata la relazione many-to-many tra le 2 tabelle, con "attach" che 
                                                  // aggiunge dei record alla tabella di associazione tra i 2 modelli
    
        return response()->json(['message' => 'Course create', 'course' => $course]); // Ritorna un messaggio di riuscita del processo
    }

    public function updateCourse(Request $request):Response { // Questa funzione permette di modificare un corso esistente 
      
        $course = Course::find($request->id); // Tramite questa query si seleziona il corso tramite "id"

        if ($course === null) { // Qualora il corso non dovesse essere presente, viene fornito un messaggio che specifica l'errore
            return response(
                "Course with id {$request->id} not found",
                Response::HTTP_NOT_FOUND
            );
        }

        if ($course->update($request->all()) === false) { // Qualora non dovesse essere possibile modificarlo, viene fornito un errore
            return response(
                "Couldn't update the course with id {$request->id}",
                Response::HTTP_BAD_REQUEST
            );
        }

        return response(['message' => 'Course update', $course]); // Qualora tutto dovesse andar bene, viene fornito un messaggio di riuscita
    }


    public function destroy(Course $course) { // Questa funzione permette di eliminare un corso esistente 
        $course->delete(); // Si richiama il metodo "delete"
        return response()->json(['message' => 'Course delete']); // Restituisce un messaggio di riuscita se il processo va bene
    }
    
}
