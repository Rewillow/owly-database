<?php

namespace App\Models;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name']; // Permette di specificare a quali parametri è concesso assegnare un valore

    public function courses() { // Si stringe una relazione many-to-many con la tabella Courses
        return $this->belongsToMany(Course::class); 
    }
}
