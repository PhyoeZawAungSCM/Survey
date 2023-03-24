<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    protected $fillable = ['survey_id'];

    public function survey(){
        return $this->belongsTo(survey::class,"id");
    }

    public function questions(){
        return $this->belongsToMany(Answer::class,'question_answers','question_id','answer_id')->withPivot('data');
    }
}
