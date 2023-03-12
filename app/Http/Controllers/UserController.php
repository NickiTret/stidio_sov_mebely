<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Hash;
use Session;

class UserController extends Controller
{
    public function create()
    {
        return view('User.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request['password'])
        ]);

        session()->flash('success', 'Регистрация прошла успешно!');

        Auth::login($user);

        return redirect()->route('home');
    }

    public function loginForm()
    {
        return view('User.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]))
        {
            session()->flash('success', 'Вы авторизированы');

            if (Auth::user()->is_admin) {
                return redirect()->route('admin');
            } else {
                return redirect()->route('home');
            }
        }

        return redirect()->back()->with('error', 'Ошибка авторизации');
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.create');
    }
}
