<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class SubjectController extends Controller
{
    //
    public function index() {
        $subject = Subject::orderBy('name')->get();
        $title = 'Subject';
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
            'name'=>'required|string|max:100',
        ]);

        try {
            DB::beginTransaction();
            Subject::create([
                'name' => $request->name,
            ]);

            DB::commit();

            return redirect('subject/create')->with('status', 'Subject created');
        } catch (QueryException $e) {
            DB::rollback();

            $errorMessage = "Subject must be unique.";

        return redirect('subject/create')->with('error', $errorMessage);
        }
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

        try {
            DB::beginTransaction();
            Subject::findOrFail($id)->update([
            'name' => $request->name,
            ]);
            DB::commit();

            return redirect('subject/')->with('status', 'Subject updated');
        } catch (QueryException $e) {
            DB::rollBack();
            $errorMessage = "Subject must be unique.";
            return redirect()->back()->with('error', $errorMessage);
        }

    }

    public function destroy(int $id) {
        if (Auth::check()) {
            $subject = Subject::findOrFail($id);
            $subject->delete();
            return redirect('subject/')->with('status', 'Subject deleted');
        } else {
            return redirect('/login')->with('loginError', 'Please login to delete subject');
        }
    }
}
