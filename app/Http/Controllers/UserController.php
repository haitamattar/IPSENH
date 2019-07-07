<?php

namespace App\Http\Controllers;

use App\Settings;
use App\Skill;
use App\UserSkills;
use Http\Message\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\User;
use App\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use PhpParser\Node\Expr\Array_;
use Psy\Util\Json;
use App\Events\searchUserEvent;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | User Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling user related functions.
    |
    */

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'string|min:6|confirmed|nullable',
            'country_id' => 'int']);
    }


    /**
     * Check if the given extension is on the allowed extensions list.
     *
     * @param string $fileExtension
     * @return boolean
     */
    public function checkIfExtensionIsAllowedFileUpload($fileExtension)
    {
        $extensions = [
            'jpg', 'jpeg', 'png', 'gif'
        ];

        if (Str::endsWith($fileExtension,  $extensions)) {
            return true;
        }
        return false;
    }

    public function checkIfFileSizeIfTooBig($file) {
        if (getimagesize($file) > 2097152){
            return false;
        }
        return true;
    }

    public function saveAvatarInDriveAndDatabase($file)
    {
        try {
            if (File::where('id', Auth::id())->first()) {
                $path = $file->store('public/avatars');
                $fileName = basename($path);
                $fileToDB = new File(['id' => Auth::id(), 'name' => $fileName, 'path' => '/storage/avatars/' . $fileName,]);
                $fileToDB->save();
            }
        } catch (\Exception $e) {
        }
    }

    public function updateAvatarInDriveAndDatabase($file)
    {
        try {
            $duplicate = File::where('id', Auth::id())->first();
            $path = $file->store('public/avatars');
            $fileName = basename($path);
            File::where('id', Auth::user()->information->profile_picture_id)->update(['name' => "goeie", 'path' => '/storage/avatars/' . $fileName, 'updated_at' => DB::raw('NOW()')]);
            $this->deleteAvatar($duplicate->name);
        } catch (\Exception $e) {
        }
    }

    public function uploadAvatar(Request $request) {
        $validator = $this->validateFile($request->all());

        if ($validator->fails()) {
            return response()->json(['message' => 'Only gif, jpg, jpeg or png files are allowed.', 400]);
        }

        $userid = Auth::id();
        $file = $request->file('file');

        $extension = $file->getClientOriginalExtension();
        // return "goeie";
        if ($this->checkIfExtensionIsAllowedFileUpload($extension)) {
            // return getimagesize($file['name']);
            try {
                $this->saveAvatarInDriveAndDatabase($file);
                $this->updateAvatarInDriveAndDatabase($file);
            } catch (\Exception $e) {
            }
            return response()->json(['message' => 'Your profile picture has been successfully uploaded.', 200]);
        } else {
            return response()->json(['message' => 'Only gif, jpg, jpeg or png files and a size of 2MB are allowed', 400]);
        }
    }



    protected function validateFile(array $data)
    {
       return Validator::make($data, [
           'file'        => 'required|max:20240|image'
       ]);
    }

    public function deleteAvatar($fileName) {
        Storage::disk('local')->delete('public/avatars/' . $fileName);
    }

    protected function editUser(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return response()->json(['status' => 500, 'error' => $validator->errors()]);
        }

        try {
            $userId = Auth::id();
            $user = User::findOrFail($userId);
            if ($user) {
                $user->fill([
                    'name' => $request['name'],
                    'surname' => $request['surname'],
                    'email' => $request['email'],
                    'country_id' => $request['country_id']
                ]);

                if ($request->password != null) {
                    $user->fill([
                        'password' => Hash::make($request['password'])]);
                }
                $user->save();
                return response()->json(
                    ['message' => 'Your settings have been successfully changed.', 200]);
            } else {
                return response()->json(['message' => 'Something went wrong.', 400]);
            }
        } catch (QueryException $queryException) {
            return response()->json(['error' => $queryException]);
        }
    }

    protected function getUserSettings()
    {
        $settings = Settings::where('user_id', Auth::id())->first();
        return $settings;
    }

    protected function changeUserSettings(Request $request)
    {
        try {
            $settings = Settings::where('user_id', Auth::id())->first();
            $settings->fill([
                'accept_invites' => $request['accept_invites'],
                'email_notifications' => $request['email_notifications']
            ]);
            $settings->save();
            return response()->json(
                ['message' => 'Your notifications have been successfully changed.', 200]);

        } catch (QueryException $queryException) {
            return response()->json(['error' => $queryException]);
        }
    }

    public function all(Request $request)
    {
        $users = User::with('information', 'information.image:id,path', 'skills.skill:id,name')->take(40)->get();
        return response()->json($users);
    }

    public function getCurrentAuthUser(){
        $user = Auth::user()->load('information', 'information.image:id,path');
        return response()->json($user);
    }

    public function search(Request $request){
        $query = $request->query('query');
        $users = User::with('information', 'information.image:id,path', 'skills.skill:id,name')
            ->where('name', 'like', '%' . $query . '%')
            ->orWhere('surname', 'like', '%' . $query . '%')
            ->take(15)
            ->get();

        //Broadcast search results with Pusher channels
        event(new searchUserEvent($users));

        return response()->json("ok");
    }

    protected function getGithubInfo(User $user) {
        $token = $user->github_api_token;

        $client = new \Github\Client();
        $client->authenticate($token, 'http_token');

        try {
            return $client->api('current_user')->show();
        } catch (\RuntimeException $exception) {
            if ('Bad credentials' === $exception->getMessage()) {
                return response()->json([
                    'error' => 'User does not have a github account connected.'
                ]);
            }
        }
    }


    protected function getGithubRepositories(User $user)
    {
        $token = $user->github_api_token;

        $client = new \Github\Client();
        $client->authenticate($token, 'http_token');

        try {
            $repositories = $client->api('current_user')->repositories();
            $count = sizeof($repositories);

            return response()->json([
                'count' => $count,
                'repositories' => $repositories,
            ]);


        } catch (\RuntimeException $exception) {
            if ('Bad credentials' === $exception->getMessage()) {
                return response()->json([
                    'error' => 'User does not have a github account connected.'
                ]);
            }
        }

    }

    protected function getProjectsFromUser(User $user)
    {
        return response()->json([
            "projects" => $user->project
        ]);
    }

    protected function getExternalAuthRedirect(User $user, String $provider, Request $request)
    {
        $token = explode(' ', $request->cookie('token'))[1];

        if (!$token) {
            return redirect('/settings')->with([
                "message" => "error: You must be logged in."
            ]);
        }

        $authUser = JWTAuth::toUser($token); // todo check if wrong


        if (isset($user[$provider . '_id'])) {
            return redirect('/settings')->with([
                "message" => "error: You already have a $provider account connected."
            ]);
        }

        if ($authUser->id === $user->id) {
            return Socialite::driver($provider)->with([
                'redirect_uri' => env('APP_URL') . "/auth/callback/$provider/addToExisting"
            ])->redirect();
        } else {
            return redirect('/settings')->with([
                "message" => "error: You can only add connections to yourself."
            ]);
        }
    }

    protected function addGithubConnectionToUserCallback(String $provider, Request $request)
    {
        $token = explode(' ', $request->cookie('token'))[1];

        $user = JWTAuth::toUser($token);

        if ('github' === $provider) {
            $data = Socialite::driver($provider)->user();
        } else {
            $data = Socialite::driver($provider)->redirectUrl(env('APP_URL') . "/auth/callback/$provider/addToExisting")->user();
        }

        $user[$provider . '_id'] = $data->id;
        $user[$provider . '_api_token'] = $data->token;

        if($user->save()) {
            return redirect('/settings')->with([
                "message" => "success: User updated scornfully"
            ]);
        }

    }

    protected function removeExternalConnectionFromUser(String $provider)
    {
        $user = auth()->user();

        $user[$provider . '_id'] = null;
        $user[$provider . '_api_token'] = null;

        if ($user->save()) {
            return response()->json([
                'message' => 'User updated succesfully'
            ]);
        }

    }

    public function getAllSkills()
    {
        return Skill::all();
    }

    public function getUserSkills()
    {
       return UserSkills::where('user_id', Auth::id())->get();
    }

    public function addSkillsToUser(Request $request)
    {
        try {
            foreach ($request->skills as $skill) {
                if($skill) {
                    $userskill = UserSkills::firstOrCreate([
                        'user_id' => Auth::id(),
                        'skill_id' => $skill]);
                    $userskill->save();
                }
            }
            return response()->json(['message' => 'Succesfully added skills.']);
        } catch (QueryException $queryException) {
            return response()->json(['error' => "Something went wrong X0"]);
        }
    }

    public function userProfile(int $userId){
        return User::with('information', 'information.image:id,path','experience','project:user_id,name','blog:user_id,name','following:user_id,following_user_id','following.following_user:id,name,surname','following.following_user.information.image:id,path','skills','skills.skill')->get()->find($userId);;
    }
}
