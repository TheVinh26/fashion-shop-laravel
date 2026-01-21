@extends('layouts.app')
@section('title', 'Verify Email')
@section('content')
<div class="min-h-[80vh] flex items-center justify-center bg-slate-50 px-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl shadow-slate-200/60 p-8 md:p-12 border border-slate-100">
        
        <div class="flex justify-center mb-8">
            <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center text-blue-600">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>

        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-slate-900 mb-4 tracking-tight">Verify Your Email</h1>
            <p class="text-slate-500 leading-relaxed">
                Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just sent to you.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center gap-3 text-emerald-700 text-sm font-medium animate-pulse">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>A new verification link has been sent to your inbox.</span>
            </div>
        @endif

        <div class="space-y-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-3.5 rounded-xl font-bold text-lg hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/25 active:scale-[0.98]">
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit" class="text-sm font-semibold text-slate-400 hover:text-slate-600 transition underline underline-offset-4">
                    Log Out
                </button>
            </form>
        </div>

        <div class="mt-12 pt-8 border-t border-slate-100 text-center">
            <p class="text-xs text-slate-400">
                Didn't receive the email? Check your spam folder or contact support if the problem persists.
            </p>
        </div>
    </div>
</div>
@endsection