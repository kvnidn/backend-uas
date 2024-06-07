<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    public function index() {
        $title = 'Login';
        return view('login/index', compact('title'));
    }

    public function authentication(Request $request) {
        $credentials = $request->validate([
           'email'=>'required|max:255|string|email',
           'password'=>'required|max:255|string',
        ]);

        if(Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->with('loginError', 'Login Error');
    }

    public function logout(Request $request) {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}
