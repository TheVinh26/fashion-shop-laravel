@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">
    <div class="max-w-4xl w-full bg-white rounded-xl shadow-2xl overflow-hidden md:flex">
        
        <div class="hidden md:block md:w-1/2 bg-blue-600 p-12 flex flex-col justify-center items-center text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Welcome Back!</h2>
            <p class="text-white text-opacity-80 mb-6">
                Log in to explore the latest fashion trends and manage your orders.
            </p>
            {{-- Placeholder cho Icon --}}
            <svg class="w-24 h-24 text-white opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <p class="mt-4 text-white text-sm">Shop Fashion</p>
        </div>

        <div class="w-full md:w-1/2 p-8 sm:p-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Sign In</h2>
            
            <form method="POST" action="{{ route('login.perform') }}">
                @csrf
                
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus
                        placeholder="you@example.com"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out @error('email') border-red-500 @enderror"
                    >
                    @error('email') 
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>
                
                <div class="mb-8">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        placeholder="••••••••"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out @error('password') border-red-500 @enderror"
                    >
                    @error('password') 
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>
                
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>
                    <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition duration-150">
                        Forgot Password?
                    </a>
                </div>
                
                <div>
                    <button 
                        type="submit" 
                        class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold text-lg hover:bg-blue-700 transition duration-300 ease-in-out transform hover:scale-[1.01] focus:outline-none focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50"
                    >
                        Login
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500">Don't have an account?</p>
                <a href="{{route('register')}}" class="text-blue-600 font-medium hover:text-blue-700 transition duration-150">
                    Sign Up now
                </a>
            </div>
        </div>
    </div>
</div>
@endsection