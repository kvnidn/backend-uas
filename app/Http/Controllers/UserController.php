<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index() {
        $user = User::orderBy('role')->orderBy('name')->get();
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
        $validator = Validator::make($request->all(),[
            'name'=>'required|max:255|string',
            'email'=>'required|max:255|string|email|unique:user,email',
            'password'=>'required|max:255|string',
            'role'=>'required',
            'remember_token'=>'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator, 'createUser')
                        ->withInput();
        }

        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'role'=>$request->role,
            'remember_token'=>$request->remember_token,
        ]);

        return redirect('user/')->with('status', 'User created');
    }

    public function edit(int $id) {
        $user = User::findOrFail($id);
        $title = "User";

        return view("/", compact('user', 'title'));
    }

    public function update(Request $request, int $id) {
        $validator = Validator::make($request->all(),[
            "name" => "required|max:255|string",
            "email" => "required|max:255|string|email|unique:user,email,{$id}",
            'password' => auth()->user()->role === 'Admin' ? "nullable|max:255|string" : "required|max:255|string",
            'new_password' => "nullable|max:255|string",
            'confirm_new_password' => "nullable|max:255|string",
            'role' => 'required',
            'remember_token'=>'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator, 'editUser')
                        ->withInput();
        }

        $user = User::findOrFail($id);

        if (auth()->user()->role !== 'Admin') {
            if (!Hash::check($request->password, $user->password)) {
                return redirect()->back()->withErrors(['password' => 'Current password is incorrect']);
            }
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];
        if (auth()->user()->role === 'Admin') {
            if (!empty($request->password)) {
                $data['password'] = bcrypt($request->password);
            }
        } else {
            if (!empty($request->new_password) || !empty($request->confirm_new_password)) {
                if ($request->new_password === $request->confirm_new_password){
                    $data['password'] = bcrypt($request->new_password);
                }
                else{
                    return redirect()->back()->withErrors(['confirm_new_password' => 'The new password and confirmation password do not match']);
                }
            }
        }

        $user->update($data);

        return redirect()->back()->with('status', 'User updated');
    }

    public function editProfile(int $id) {
        $user = User::findOrFail($id);
        $title = "User";

        return view("/", compact('user', 'title'));
    }

    public function updateProfile(Request $request, int $id) {
        // Define base validation rules
        $rules = [
            "name" => "required|max:255|string",
            "email" => "required|max:255|string|email|unique:user,email,{$id}",
            'role' => 'required',
            'password' => 'required',
            'remember_token' => 'nullable|string',
        ];
    
        // Add password validation based on user role
        if (auth()->user()->role === 'Admin') {
            $rules['password'] = "nullable|max:255|string";
        } else {
            // If user is not admin, check if new_password or confirm_new_password is provided
            if (!empty($request->input('new_password')) || !empty($request->input('confirm_new_password'))) {
                $rules['new_password'] = "required|max:255|string";
                $rules['confirm_new_password'] = "required|max:255|string|same:new_password";
            }
        }
    
        // Validate the request
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator, 'editProfile')
                        ->withInput();
        }
    
        $user = User::findOrFail($id);
    
        // Validate current password if user is not admin
        if (auth()->user()->role !== 'Admin') {
            if (!Hash::check($request->input('password'), $user->password)) {
                return redirect()->back()->with(['password' => 'Current password is incorrect']);
            }
        }
    
        // Prepare data for update
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role' => $request->input('role'),
        ];
    
        // Handle password update based on user role
        if (auth()->user()->role === 'Admin' && !empty($request->input('password'))) {
            $data['password'] = bcrypt($request->input('password'));
        } elseif (!empty($request->input('new_password'))) {
            $data['password'] = bcrypt($request->input('new_password'));
        }
    
        // Update user record
        $user->update($data);
    
        return redirect()->back()->with('status', 'User updated');
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

    // NOT USED
    public function createUser() {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('12345'),
            'role' => 'Admin', // Set the role as appropriate
            'remember_token'=>null,
        ]);

        return 'User created successfully!';
    }





}
