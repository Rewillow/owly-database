<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class SubjectsController extends Controller
{
    public function subjectsList() { // Questa funzione permette di ricevere tutte le materie presenti nel database oltre che il filtraggio
        $subjects = Subject::query() // Questa query per il filtraggio permette di cercare le materie per "name"
        ->when(request()->has('name'), function($query) {
            return $query->where('name', 'like', '%'.request('name'). '%');
        })
        ->get(); // Viene mandata la richiesta della query

        return response()->json($subjects); // Ritorna i risultati in formato "json"
    }

    public function showSubject($id) { // Questa funziona mostra la singola materia e i rispettivi dettagli
        $subject = Subject::find($id); // La variabile e la rispettiva query vanno alla ricerca dell'id della materia
        return response()->json($subject); // Ritorna i risultati in formato "json"
    }

    public function storeSubject(Request $request) { // Questa funzione permette di creare una nuova materia

        $data = $request->json()->all(); // Tramite questa variabile si fa una richiesta HTTP che ritorna tutti i parametri richiesti in formato json
    
        $subject = new Subject([ // Tramite questa variabile, dove viene richiamata la classe "Subject" stabiliamo quali dati vogliamo creare
            'name' => $data['name'],
        ]);
    
        $subject->save(); // In questo modo viene salvata la materia cosi da assegnare il rispettivo ID
    
        return response()->json(['message' => 'Subject create', $subject]); // Ritorna un messaggio di riuscita del processo
    }

    public function updateSubject(Request $request):Response { // Questa funzione permette di modificare una materia
      
        $subject = Subject::find($request->id); // Tramite questa query si seleziona la materia tramite "id"

        if ($subject === null) { // Qualora la materia non dovesse essere presente, viene fornita un messaggio che specifica l'errore
            return response(
                "subject with id {$request->id} not found",
                Response::HTTP_NOT_FOUND
            );
        }

        if ($subject->update($request->all()) === false) { // Qualora non dovesse essere possibile modificarla, viene fornita un errore
            return response(
                "Couldn't update the subject with id {$request->id}",
                Response::HTTP_BAD_REQUEST
            );
        }

        return response(['message' => 'Subject update', $subject]); // Qualora tutto dovesse andar bene, viene fornito un messaggio di riuscita
    }

    public function destroy(Subject $subject) { // Questa funzione permette di eliminare una materia esistente
        $subject->delete(); // Si richiama il metodo "delete"
        return response()->json(['message' => 'Subject delete']); // Restituisce un messaggio di riuscita se il processo va bene
    }
}
