<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    //
    public function index() {
        $subject = Subject::orderBy('id')->get();
        $title = 'subject';
        return view('subject/index', compact('subject', 'title'));
    }

    public function create() {
        $title = 'Subject';
        return view('subject/create', [
            'title'=>$title,
        ]);
    }

    public function store(Request $request) {


        $request->validate([
            'name'=>'required|string|max:50',
        ]);


        Subject::create([
            'name' => $request->name,
        ]);

        return redirect('subject/create')->with('status', 'Subject created');
    }

    public function edit(int $id) {
        $subject = Subject::findOrFail($id);
        $title = "Subject";

        return view("subject/edit", compact('subject', 'title'));
    }

    public function update(Request $request, int $id) {

        $request->validate([
            'name' => 'required|string|max:50',
        ]);


        Subject::findOrFail($id)->update([
            'name' => $request->name,
        ]);

        return redirect('subject/')->with('status', 'Subject updated');
    }

    public function destroy(int $id) {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return redirect('subject/')->with('status', 'subject deleted');
    }
}
