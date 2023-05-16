<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuth extends Controller
{
    public function signup(Request $request) {
    //  {
    //    "fio": "Ivanov Ivan",
    //    "email": "ivanov@ivan.kz",
    //    "password": "asdf1234",
    //    "birtday": "2006-04-15",
    //    "gender_id": 1
    // }
        $valid_data = $request->validate([
            'fio'=>['required','min:2','max:150'],
            'email'=>['required','email','unique','min:4','max:50'],
            'password'=>['required','min:6','max:32'],
            'birthday'=>['required','data'],
            'gender_id'=>['required','exists:genders,id'],
        ]);

        $user = User::create($valid_data);

        if ($user) {
            $token = $user->createToken('kinotower')->plainTextToken;

            return response()->json([
                "status"=> "success",
                "token"=> $token,
                "id"=> $user->id,
                "fio"=> $valid_data['fio']
            ], 201);
        }
        return "ERROR";

    }
    public function signin(Request $request) {
        $valid_data = $request->validate([
            'email'=>['required','email','unique','min:4','max:50'],
            'password'=>['required','min:6','max:32'],
        ]);

        if (Auth::attempt($valid_data)) {
            $user = (object) Auth::user();
            $token = $user->createToken('kinotower')->plaunTextToken;
            return response()->json([
                "status"=> "success",
                "token"=> $token,
                "id"=> $user->id,
                "fio"=> $valid_data['fio']
            ], 200);
        }

        return response()->json([
            "status"=> "invalid",
            "message"=> "Wrong email or password"
        ], 401);

    }
    public function signout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            "status"=> "success"
        ], 200);
    }
}
