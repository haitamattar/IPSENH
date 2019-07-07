<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'country_id',
        'role',
        'github_id',
        'github_api_token',
        'gitlab_id',
        'gitlab_api_token',
        'bitbucket_id',
        'bitbucket_api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'email',
        'email_verified_at',
        'role',
        'deleted_at',
        'password',
        'created_at',
        'updated_at',
        'remember_token',
        'github_api_token',
        'gitlab_api_token',
        'bitbucket_api_token',
    ];

    /**
     * Get all the projects of the user.
     */
    public function project()
    {
        return $this->hasMany('App\Project');
    }

    /**
     * Get all the articles of the user.
     */
    public function article()
    {
        return $this->hasMany('App\Article');
    }

    /**
     * Get the settings of the user.
     */
    public function settings()
    {
        return $this->belongsTo('App\Settings');
    }

    /**
     * Get the user information
     */
    public function information()
    {
        return $this->hasOne('App\UserInformation');
    }


    /**
     * Get all the skills of the user.
     */
    public function skills()
    {
        return $this->hasMany('App\UserSkills');
    }

    public function experience()
    {
        return $this->hasMany('App\Experience');
    }

    public function following()
    {
        return $this->hasMany('App\Following', 'following_user_id');
    }

    public function blog()
    {
        return $this->hasMany('App\Project');
    }

}


class UserInformation extends Model
{
    protected $table = "user_information";

    protected $fillable = [
        'user_id', 'bio', 'profile_picture_id', 'search_job'
    ];

    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    /**
     * Get the user profile picture
     */
    public function image()
    {
        return $this->belongsTo('App\File', 'profile_picture_id');
    }
}


class UserSkills extends Model {

    protected $table = "user_skill";
    protected $fillable = [
        'user_id',
        'skill_id'

    ];
    protected $hidden = [
        'created_at',
        'updated_at',
//        'skill_id',
//        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function skill()
    {
        return $this->belongsTo('App\Skill');
    }

}
