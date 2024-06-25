<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

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
        return view('subject/', [
            'title'=>$title,
        ]);
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(),[
            'name'=>'required|unique:subject,name|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator, 'createSubject')
                        ->withInput();
        }

        try {
            DB::beginTransaction();
            Subject::create([
                'name' => $request->name,
            ]);

            DB::commit();

            return redirect('subject/')->with('status', 'Subject created');
        } catch (QueryException $e) {
            DB::rollback();

            $errorMessage = "Subject must be unique.";

        return redirect('subject/')->with('error', $errorMessage);
        }
    }

    public function edit(int $id) {
        $subject = Subject::findOrFail($id);
        $title = "Subject";

        return view("subject/edit", compact('subject', 'title'));
    }

    public function update(Request $request, int $id) {

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:50|unique:subject,name,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator, 'editSubject')
                        ->withInput();
        }

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
