<?php

namespace App\Models;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class survey extends Model
{
    use HasFactory;use HasSlug;

    protected $fillable = ['title','description','image','status','create_user_id','expire_date'];

    public function question(){
        return $this->hasMany(Question::class,'survey_id');
    }
    public function answer(){
        return $this->hasMany(Answer::class,'survey_id');
    }
    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
}
