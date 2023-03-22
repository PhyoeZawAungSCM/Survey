<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class survey extends Model
{
    use HasFactory;
    protected $fillable = ['title','description','image','status','create_user_id','expire_date'];

    public function question(){
        return $this->hasMany(Question::class,'survey_id');
    }
}
