<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    protected $fillable = ['survey_id','name'];

    public function survey(){
        return $this->belongsTo(survey::class,"survey_id");
    }

    public function questions(){
        return $this->belongsToMany(Question::class,'question_answers','question_id','answer_id')->withPivot('data');
    }

    public function data(){
        return $this->hasMany(QuestionAnswer::class,'question_id','id');
    }
}
