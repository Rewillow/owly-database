<?php

namespace App\Models;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    protected $fillable = ['title', 'spots']; // Permette di specificare a quali parametri è concesso assegnare un valore

    use HasFactory;

    public function subjects() { // Si stringe una relazione many-to-many con la tabella Subjects
    return $this->belongsToMany(Subject::class); 
    }

}
