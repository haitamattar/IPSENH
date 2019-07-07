<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class ProjectExternalUrl extends Model {

    protected $table = "project_external_url";

    protected $fillable = [
        'project_id', 'provider_name', 'external_id', 'name', 'url'
    ];

    public function project() {
        return $this->belongsTo('App\Project');
    }

}