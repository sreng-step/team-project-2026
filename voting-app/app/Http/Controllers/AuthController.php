<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(){
        return view('auth.login');
    }
    public function loginVerify(Request $request) {
        $validated = $request->validate([
            'email'=>'required',
            'password'=>'required'
        ]);
        if(Auth::attempt($validated)){
            return redirect()->intended('/dashboard');
        }
        return back()->with('status', 'Invalid email or password');
    }
    public function register(){
        return view('auth.register');
    }
    public function registerStore(Request $request){
        $validated = $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|confirmed'
        ]);
        \App\Models\User::create($validated);
        return redirect('/login')->with('status', 'Registration successful. Please login.');
    }
}
