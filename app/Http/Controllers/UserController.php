<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index() {
        $user = User::orderBy('id')->get();
        $title = 'User';
        return view('user/index', compact('user', 'title'));
    }

    public function create() {
        $title = 'User';
        return view('user/create', [
            'title'=>$title,
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'name'=>'required|max:255|string',
            'email'=>'required|max:255|string|email|unique:user,email',
            'password'=>'required|max:255|string',
            'role'=>'required',
        ]);

        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'role'=>$request->role,
        ]);

        return redirect('user/create')->with('status', 'User created');
    }

    public function edit(int $id) {
        $user = User::findOrFail($id);
        $title = "User";

        return view("user/edit", compact('user', 'title'));
    }

    public function update(Request $request, int $id) {
        $request->validate([
            "name" => "required|max:255|string",
            "email" => "required|max:255|string|email|unique:user,email,{$id}",
            'password' => "required|max:255|string",
            'role' => 'required',
        ]);

        $user = User::findOrFail($id);

        User::findOrFail($id)->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'role'=>$request->role,
        ]);

        return redirect('user/')->with('status', 'User updated');
    }

    public function destroy(int $id) {
        if (Auth::check()) {
            $user = User::findOrFail($id);
            $user->delete();
            return redirect('user/')->with('status', 'User deleted');
        } else {
            return redirect('/login')->with('loginError', 'Please login to delete users');
        }
    }



}
