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

    // Display form register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle register
    public function register(Request $request)
    {
        // 1. Validate
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // 2. Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', 
        ]);

        // 3. Auto login
        Auth::login($user);

        // 4. Redirect
        // return redirect('/')->with('success', 'Register successful!');
        return redirect('/');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt login

        // Code Old
        // if (Auth::attempt($credentials, $request->boolean('remember'))) {
        //     // Authentication passed...
        //     $request->session()->regenerate();

        //     // Redirect intended (if the user was previously blocked by authentication middleware)
        //     return redirect()->intended(url('/')); // or route('checkout') default
        // }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // redirect by role
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect('/');
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        // invalidate session + regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
