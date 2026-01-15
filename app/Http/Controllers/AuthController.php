<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Display form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Display form register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle register
    public function register(Request $request)
    {
        // // 1. Validate
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|email|unique:users,email',
        //     'password' => 'required|min:6|confirmed',
        // ]);

        // // 2. Create user
        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        // ]);

        // // 3. Auto login
        // Auth::login($user);

        // // 4. Redirect
        // return redirect('/')->with('success', 'Register successful!');

        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:6|confirmed',
        ]);

        User::registerUser($data);

        return redirect('/')->with('success', 'Register successful!');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        User::loginUser(
            $credentials,
            $request->boolean('remember')
        );

        return redirect()->intended('/');
    }

    // Logout
    public function logout(Request $request)
    {        
        User::logoutUser();

        return redirect('/login');
    }
}
