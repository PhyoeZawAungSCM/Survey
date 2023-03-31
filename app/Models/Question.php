<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = ['survey_id','title','description','type','data'];

    public function answers(){
        return $this->belongsToMany(Answer::class,'question_answers','question_id','answer_id')->withPivot('data');
    }
    public function answer(){
        return $this->hasMany(QuestionAnswer::class,'question_id','id');
    }
}
