<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    function login() {
        if(auth()->check()) return redirect()->to('/' . auth()->user()->role); //redirect sesuai role
        return view('guest.auth.login', ['title'=>'Login']);
    }
    function loginAttempt(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if(Auth::attempt($credentials, $request->remember ?? false)) {
            $request->session()->regenerate();
            return redirect()->to('/' . auth()->user()->role)->with('message', 'Welcome '.auth()->user()->name); //redirect sesuai role
        }
        
        return redirect()->back()->withErrors(['email' => 'The provided credentials do not match our records.'])->withInput(['email']);
    }
    
    function register() {
        return view('guest.auth.register', ['title'=>'Register']);
    }
    function registerAttempt(Request $request) {
        $data = $request->validate([
            'name' => ['required', 'min:4'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:4'],
            'password_confirmation' => ['required', 'same:password'],
            'terms' => ['required']
        ]);

        User::insert([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => ($request->role === 'seller') ? 'seller' : 'buyer'
        ]);

        return redirect()->to('/login')->with('message', 'Register Successfull');
    }

    function logout() {
        auth()->logout();
        return redirect()->to('/login');
    }
}
