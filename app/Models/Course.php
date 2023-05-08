<?php

namespace App\Models;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    protected $fillable = ['title', 'spots'];

    use HasFactory;

    public function subjects() {
    return $this->belongsToMany(Subject::class); 
    }

}
