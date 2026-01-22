<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;


class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /* =====================
     | BUSINESS LOGIC
     ===================== */

    /**
     * Register new user and auto login
     */
    public static function registerUser(array $data): self
    {
        $validator = Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $user = self::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (app()->environment('local')) {
            $user->markEmailAsVerified(); //Verify Now
        } else {
            event(new Registered($user));
        }

        // Auth::login($user);

        // Handle verify email
        // event(new Registered($user));

        return $user;
    }

    /**
     * Attempt login user
     */
    public static function loginUser(array $credentials, bool $remember = false): void
    {
        $validator = Validator::make($credentials, [
        'email'    => ['required', 'email'],
        'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        if (! Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        request()->session()->regenerate();
    }

    /**
     * Logout user safely
     */
    public static function logoutUser(): void
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
   /* =====================
     | HELPERS
     ===================== */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
