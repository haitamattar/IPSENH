<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectDescription extends Model
{
    protected $table = "project_description";
    
    protected $fillable = [
        'project_id','type', 'title', 'order', 'description', 'image_id'
    ];

    protected $with = [
        'image'
    ];

    /**
    * Get the project that owns the description.
    */
    public function project() {
        return $this->belongsTo('App\Project');
    }


    public function image() {
        return $this->hasOne(File::class, 'id', 'image_id');
    }

}
