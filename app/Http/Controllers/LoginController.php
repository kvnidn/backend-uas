<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    //
    public function index() {
        $title = 'Login';
        return view('login/index', compact('title'));
    }

    public function authentication(Request $request) {
        $validator = Validator::make($request->all(),[
           'email'=>'required|max:255|string|email',
           'password'=>'required|max:255|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator, 'login')
                        ->withInput();
        }

        $credentials = $request->only('email', 'password');

        $remember = $request->filled('remember');

        if(Auth::attempt($credentials, $remember)) {
            if($remember) {
                $request->session()->regenerate();
            }

            return redirect()->intended('/');
        }

        return back()->with('loginError', 'Incorrect e-mail or password');
    }

    public function logout(Request $request) {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }

}
