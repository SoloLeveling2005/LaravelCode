<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthorization extends Controller
{
    public function login(Request $request)
    {
        if ($request->method() == "POST") {
            // Validate request data
            // 'exists:organizers,email'
            $valid_data = $request->validate([
                'email' => ['required'],
                'password' => ['required']
            ]);


            // If organizer exists in db
            if (Auth::guard('organizer')->attempt(['email' => $valid_data['email'], 'password' => $valid_data['password']])) {
                return redirect(route('events'));
            }
        }

        return view('index');
    }
}
