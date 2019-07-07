<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $table="skill";
    protected $fillable = [
        'id',
        'name'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
