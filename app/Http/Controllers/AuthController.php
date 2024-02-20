<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed',
            'role' => 'required|in:superadmin,admin,director,ceo,manager,teamlead,projectmanager,hr,hrassist,sales,developer,client,guest',
        ]);

        // Set default role to 'guest' if not provided in the request
        $userData = array_merge($data, ['role' => $data['role'] ?? 'guest']);


        // Mass assign the validated request data to a new instance of the User model
        $user = User::create($userData);
        $token = $user->createToken('my-token')->plainTextToken;

        return redirect('/')->with([
            'token' => $token,
            'Type' => 'Bearer'
        ]);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'useroremail' => 'required',
            'password' => 'required',
        ]);

        $user = User::orwhere('name', $fields['useroremail'])->orwhere('email', $fields['useroremail'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Wrong credentials'
            ]);
        }

        $token = $user->createToken('my-token')->plainTextToken;

        // Redirect the user to the home view after a successful login
        return redirect()->route('home')->with([
            'token' => $token,
            'Type' => 'Bearer',
            'role' => $user->role // include user role in response
        ]);
    }

    public function home(Request $request)
    {
        return view('home');
    }

    public function registerpage(Request $request){
        return view('register');
    }
}
