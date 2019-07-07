<?php

namespace App\Http\Controllers\Auth;

use App\Settings;
use App\User;
use App\File;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use InvalidArgumentException;
use Laravel\Socialite\Facades\Socialite;
use phpDocumentor\Reflection\Types\Boolean;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
       return Validator::make($data, [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'country_id' => 'int']);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param Request $request
     * @return \App\User
     */
    protected function create(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return  response()->json(['status'=>500,'error'=>$validator->errors()]);
        }

        try{
            $user = User::create([
                'name' => $request['name'],
                'surname' => $request['surname'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'country_id' => $request['country_id'],
                'role' => 'USER',
                'deleted' => false
            ]);

            $this->createSettings($user->id);

            $image = $this->createStandardProfileImage();

            $userInformation = [
                'profile_picture_id' => $image['id'],
                'bio' => "My bio text",
                'search_job' => true,
            ];

            $user->information()->create($userInformation);

            return $user;
        }catch(QueryException $queryException){
            return response()->json(['error'=>$queryException]);
        }
    }


    private function createStandardProfileImage(){
        $image = [
            'name' => "StandardProfile",
            'path' => "/images/profile.png",
        ];

        $image = File::create($image);
        return $image;
    }


    protected function createSettings($userID) {
        try{
            $settings = Settings::create([
                'user_id' => $userID,
                'accept_invites' => false,
                'email_notifications' => false,
            ]);

            return $settings;
        }catch(QueryException $queryException){
            return response()->json(['error'=>$queryException]);
        }
    }

    /**
     * For supported providers check config/service.php
     * @param String $provider
     * @return \Illuminate\Http\JsonResponse
     */
    public function redirectToExternalSignIn(String $provider) {
        try {
            return Socialite::driver($provider)->redirect();
        } catch(InvalidArgumentException $invalidArgumentException) {
            return response()->json([
                'error' => 'This provider is not supported.' // TODO trans
            ]);
        }

    }

    /**
     * For supported providers check config/service.php
     */
    public function handleExternalSignInCallback(String $provider) {
        $data = Socialite::driver($provider)->user();

        try {
            $user = $this->createUserForService($data, $provider);
            $this->createSettings($user->id);
            $token = $this->getBearerTokenForUser($user);

            $image = $this->createStandardProfileImage();

            $userInformation = [
                'profile_picture_id' => $image['id'],
                'bio' => "My bio text",
                'search_job' => true,
            ];

            $user->information()->create($userInformation);
            
            return redirect('/settings')->with([
                'loggedIn' => 'true',
                'token' => 'Bearer ' . $token
            ]);

        } catch (QueryException $queryException) {
            $user = $this->getUserByProviderId($provider, $data->id);

            if(null === $user) {
                return view('welcome', [
                    'loggedIn' => 'false',
                    'token' => ''
                ]);
            }

            $token = $this->getBearerTokenForUser($user);
            return redirect('/settings')->with([
                'loggedIn' => 'true',
                'token' => 'Bearer ' . $token
            ]);


        }



    }

    private function createUserForService($data, String $provider) {
        return User::create([
            'name' => explode(' ', $data->name)[0],
            'surname' => explode(' ', $data->name)[1],
            'password' => null,
            'email' => $data->email,
            'role' => 'USER',
            $provider . '_id' => $data->id,
            $provider . '_api_token' => $data->token,
            'deleted' => false
        ]);
    }

    private function getUserByProviderId(string $provider, $user_id) {
        $field_name = $provider . '_id';
        return User::where($field_name, $user_id)->first();
    }

    public function getBearerTokenForUser(User $user) {
        return JWTAuth::fromUser($user);
    }

}
