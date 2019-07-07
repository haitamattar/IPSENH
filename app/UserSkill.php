<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSkill extends model{
    protected $table='user_skill';

    protected $fillable=['user_id','skill_id'];

    public function skill(){
        return $this->hasOne('App\Skill','id','skill_id');
    }
}