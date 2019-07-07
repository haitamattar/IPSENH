<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{

    protected $fillable = [
        'user_id','title','description', 'story','private', 'views', 'price'
    ];

    /**
    * Get the user that owns the article.
    */
   public function user()
   {
       return $this->belongsTo('App\User');
   }
}
