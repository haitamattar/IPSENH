<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Experience;
use Illuminate\Support\Facades\Validator;

class ExperienceController extends Controller
{
    //

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
       return Validator::make($data, [
           'name'        => 'required',
           'jobTitle'    => 'required',
           'description' => 'required',
           'startDate'   => 'required|date',
           'endDate'     => 'required|date']);
    }

    public function add(Request $request){
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return  response()->json(['error'=>$validator->errors()], 500);
        }

        $currentUserId = Auth::id();

        $experience = Experience::create([
            'user_id' => $currentUserId,
            'name' => $request['name'],
            'description' => $request['description'],
            'type' => $request['jobTitle'],
            'start_date' => $request['startDate'],
            'end_date' => $request['endDate'],
        ]);

        return $experience;
    }

    public function remove(){

    }
}
