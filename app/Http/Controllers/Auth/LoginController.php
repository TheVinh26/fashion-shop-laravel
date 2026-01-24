<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        User::registerUser($request->all());

        return redirect('/');
    }

    public function login(Request $request)
    {
        User::loginUser(
            $request->only('email', 'password'),
            $request->boolean('remember')
        );

        // Redirect theo role
        // if (auth()->user()->isAdmin()) {
        //     return redirect()->route('admin.dashboard');
        // }

        // return redirect()->intended('/');

        // Handle verify email
        // if (! auth()->user()->hasVerifiedEmail()) {
        //     Auth::logout();
        //     return redirect('/login')
        //         ->withErrors(['email' => 'Please verify your email address before logging in.']);
        // }

        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended('/');
    }

    public function logout(Request $request)
    {
        User::logoutUser();

        return redirect('/login');
    }
}
