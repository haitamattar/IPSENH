<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $table = "experience";

    protected $fillable = [
        'id', 'user_id', 'name', 'type', 'description', 'start_date', 'end_date'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
