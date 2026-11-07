<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOGIN_DECAY_SECONDS = 300;

    public function loginForm()
    {
        return view('User.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $throttleKey = 'login:' . Str::lower($credentials['email']) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_LOGIN_ATTEMPTS)) {
            $minutes = (int) ceil(RateLimiter::availableIn($throttleKey) / 60);

            throw ValidationException::withMessages([
                'email' => "Слишком много попыток входа. Попробуйте через {$minutes} мин.",
            ]);
        }

        if (!Auth::attempt($credentials)) {
            RateLimiter::hit($throttleKey, self::LOGIN_DECAY_SECONDS);

            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('error', 'Ошибка авторизации');
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        if (!Auth::user()->is_admin) {
            // Вход нужен только администраторам — остальных не держим в сессии.
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login.create')->with('error', 'Ошибка авторизации');
        }

        session()->flash('success', 'Вы авторизированы');

        return redirect()->intended(route('admin'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.create');
    }
}
