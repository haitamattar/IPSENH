<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table="project";

    protected $fillable = [
        'user_id', 'name', 'header_image_id', 'description'
    ];

    protected $with = [
        'descriptions', 'headerImage', 'externalUrl'
    ];

    /**
    * Get the user that owns the project.
    */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
    * Get the descriptions for the Project.
    */
    public function descriptions()
    {
        return $this->hasMany('App\ProjectDescription');
    }

    public function externalUrl() {
        return $this->hasOne('App\ProjectExternalUrl');
    }

    public function headerImage() {
        return $this->hasOne(File::class, 'id', 'header_image_id');
    }

    public function withAll() {
        return $this->with(['description', 'headerImage', 'externalUrl'], ['description.image:id,path', 'headerImage.image:id,path']);
    }

}
