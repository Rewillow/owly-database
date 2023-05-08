<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class SubjectsController extends Controller
{
    public function subjectsList() {
        $subjects = Subject::query()
        ->when(request()->has('name'), function($query) {
            return $query->where('name', 'like', '%'.request('name'). '%');
        })
        ->get();

        return response()->json($subjects);
    }

    public function showSubject($id) {
        $subject = Subject::find($id);
        return response()->json($subject);
    }

    public function storeSubject(Request $request) {

        $data = $request->json()->all();
    
        $subject = new Subject([
            'name' => $data['name'],
        ]);
    
        $subject->save();
    
        return response()->json(['message' => 'Subject create', $subject]); 
    }

    public function updateSubject(Request $request):Response {
      
        $subject = Subject::find($request->id);

        if ($subject === null) {
            return response(
                "subject with id {$request->id} not found",
                Response::HTTP_NOT_FOUND
            );
        }

        if ($subject->update($request->all()) === false) {
            return response(
                "Couldn't update the subject with id {$request->id}",
                Response::HTTP_BAD_REQUEST
            );
        }

        return response(['message' => 'Subject update', $subject]);
    }

    public function destroy(Subject $subject) {
        $subject->delete();
        return response()->json(['message' => 'Subject delete']);
    }
}
