<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Following extends Model
{
    protected $table = "following";

    protected $fillable = ['user_id','following_user_id'];

    public function following_user(){
        return $this->belongsTo('App\User','user_id');
    }
}
