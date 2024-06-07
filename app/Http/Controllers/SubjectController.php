<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    //
    public function index() {
        $subject = Subject::orderBy('id')->get();
        $title = 'Subject';
        return view('subject/index', compact('subject', 'title'));
    }

    public function create() {
        $title = 'Subject';
        $users = User::where('role', 'Lecturer')->get();
        return view('subject/create', [
            'title'=>$title,
            'users'=>$users
        ]);
    }

    public function store(Request $request) {


        $request->validate([
            'name'=>'required|string|max:50',
            'user_id'=>'required|exists:user,id'
        ]);


        Subject::create([
            'name' => $request->name,
            'user_id' => $request->user_id,
        ]);

        return redirect('subject/create')->with('status', 'Subject created');
    }

    public function edit(int $id) {
        $subject = Subject::findOrFail($id);
        $title = "Subject";
        $users = User::where('role', 'Lecturer')->get();

        return view("subject/edit", compact('subject', 'title', 'users'));
    }

    public function update(Request $request, int $id) {

        $request->validate([
            'name' => 'required|string|max:50',
            'user_id'=>'required|exists:user,id'
        ]);


        Subject::findOrFail($id)->update([
            'name' => $request->name,
            'user_id' => $request->user_id
        ]);

        return redirect('subject/')->with('status', 'Subject updated');
    }

    public function destroy(int $id) {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return redirect('subject/')->with('status', 'Subject deleted');
    }
}
