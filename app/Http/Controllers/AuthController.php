<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

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

        // Generate a token for the registered user
        $token = JWTAuth::fromUser($user);

        return redirect('/')->with(['token' => $token]);
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

        // Use the correct credentials array when attempting to generate a token
        $credentials = [
            'email' => $user->email,
            'password' => $fields['password'],
        ];

        // Attempt to generate a token for the user
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Redirect the user to the home view after a successful login
        return redirect()->route('home')->with([
            'token' => $token,
        ]);
    }

    public function home(Request $request)
    {
        return view('admin/home');
    }

    public function registerpage(Request $request)
    {
        return view('register');
    }
}
