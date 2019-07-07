<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('jwt.auth')->get('/users', function (Request $request) {
    return auth()->user();
});
Route::middleware('jwt.auth')->get('me/id',function(){
    return auth()->user()->id;
});

Route::middleware('jwt.auth')->get('user/isFollowing/{id}',function(int $id) {
    $user_id = auth()->user()->id;
    $following = App\Following::where('user_id','=',$user_id)->where('following_user_id','=',$id)->get();
    return ['isFollowing'=>1==$following->count()];
});

Route::middleware('jwt.auth')->get('user/isOwner/{id}',function(int $id) {
    $user_id = auth()->user()->id;
    return ['isOwner'=>$id == $user_id];
});

Route::middleware('jwt.auth')->post('user/follow/{id}',function(int $id) {
    $user = auth()->user();
    $user_id = $user->id;
    $following = App\Following::where('user_id','=',$user_id)->where('following_user_id','=',$id)->first();
    if(null==$following){
        $following = new App\Following;
        $following->user_id = $user_id;
        $following->following_user_id = $id;
        $following->save();
        // return ['user_id'=>$user_id,'following_user'=>$following->following_user];
        return  response()->json(['message'=>"Followed"], 200);
    }
});

Route::middleware('jwt.auth')->group(function () {
    Route::post('project/create', 'Project\ProjectController@store');
    Route::post('upload/avatar', 'UserController@uploadAvatar');
    Route::post('experience/add', 'ExperienceController@add');
});

Route::middleware('jwt.auth')->get('/me', function (Request $request) {
    return auth()->user();
});

Route::namespace('Project')->group(function () {
    Route::get('project', 'ProjectController@show');
    Route::get('project/{project}', 'ProjectController@getProjectById');

    Route::middleware('jwt.auth')->group(function () {
        Route::post('project/create', 'ProjectController@store');
    });
});

// TODO: namespace maybe?
Route::get('user/overviewUsers', 'UserController@overviewUsers');
Route::get('user/{user}/projects', 'UserController@getProjectsFromUser');
Route::get('skills/all', 'UserController@getAllSkills');
Route::get('user/skills', 'UserController@getUserSkills');

Route::middleware('jwt.auth')->group(function () {
    Route::post('upload/avatar', 'UserController@uploadAvatar');
    Route::delete('user/removeConnection/{provider}', 'UserController@removeExternalConnectionFromUser');
    Route::get('download/avatar', 'UserController@getAvatarUrl');
    Route::post('update/user', 'UserController@editUser');
    Route::get('user/settings', 'UserController@getUserSettings');
    Route::post('user/settings', 'UserController@changeUserSettings');
    Route::get('user/getCurrentUser', 'UserController@getCurrentAuthUser');
    Route::post('user/addSkills', 'UserController@addSkillsToUser');

    Route::delete('user/removeConnection/{provider}', 'UserController@removeExternalConnectionFromUser');
    Route::post('experience/add', 'ExperienceController@add');
});

Route::namespace('Project')->group(function () {
    Route::get('project', 'ProjectController@show');
    Route::get('project/{project}', 'ProjectController@getProjectById');
});

Route::get('project', 'Project\ProjectController@show');
Route::get('users', 'UserController@all');
Route::get('users/search', 'UserController@search');

Route::namespace('External')->group(function () {
    Route::get('user/{user}/github', 'GithubController@getGithubUserInfoEndpoint');
    Route::get('user/{user}/github/repositories', 'GithubController@getGithubUserRepositoriesEndpoint');
    Route::get('user/{user}/github/repository/{identifier}', 'GithubController@getGithubUserRepositoryEndpoint');
    Route::get('user/{user}/github/repository/{identifier}/languages', 'GithubController@getGithubUserRepositoryLanguagesEndpoint');

    Route::get('user/{user}/gitlab', 'GitlabController@getGitlabUserInfoEndpoint');
    Route::get('user/{user}/gitlab/repositories', 'GitlabController@getGitlabUserRepositoriesEndpoint');
    Route::get('user/{user}/gitlab/repository/{identifier}', 'GitlabController@getGitlabUserRepositoryEndpoint');
    Route::get('user/{user}/gitlab/repository/{identifier}/languages', 'GitlabController@getGitlabUserRepositoryLanguagesEndpoint');


    Route::get('user/{user}/bitbucket', 'BitbucketController@getBitbucketUserInfoEndpoint');
    Route::get('user/{user}/bitbucket/repositories', 'BitbucketController@getBitbucketUserRepositoriesEndpoint');
    Route::get('user/{user}/bitbucket/repository/{identifier}', 'BitbucketController@getBitbucketUserRepositoryEndpoint');
    Route::get('user/{user}/bitbucket/repository/{identifier}/languages', 'BitbucketController@getBitbucketUserRepositoryLanguagesEndpoint');

});

Route::get('user/profile/{user}','UserController@userProfile');

Route::namespace('Auth')->group(function () {
    Route::post('login/token', 'LoginController@getToken');
    Route::post('register', 'RegisterController@create');
});

Route::get('/country/all',function(){
    return App\Country::all();
});

Route::get('/country/{country}',function(App\Country $country) {
    return $country['name'];
});
