<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subject;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    //
    public function index() {
        $assignment = Assignment::orderBy('id')->get();
        $title = 'Assignment';
        return view('assignment/index', compact('assignment', 'title'));
    }

    public function create() {
        $title = 'Assignment';
        $users = User::where('role', 'Lecturer')->get();
        $subjects = Subject::all();
        $kelas = Kelas::all();
        return view('assignment/create', [
            'title'=>$title,
            'users'=>$users,
            'subjects'=>$subjects,
            'kelas'=>$kelas,
        ]);
    }

    public function store(Request $request) {


        $request->validate([
            'subject_id'=>'required|exists:subject,id',
            'user_id'=>'required|exists:user,id',
            'kelas_id'=>'required|exists:kelas,id',
        ]);

        try {
            DB::beginTransaction();

            Assignment::create([
                'subject_id' => $request->subject_id,
                'user_id' => $request->user_id,
                'kelas_id' => $request->kelas_id,
            ]);

            DB::commit();

            return redirect('assignment/create')->with('status', 'Assignment created');
        } catch (QueryException $e) {
            DB::rollBack();

            $errorMessage = "The combination of subject and user and class must be unique.";

            return redirect('assignment/create')->with('error', $errorMessage);
        }
    }

    public function edit(int $id) {
        $assignment = Assignment::findOrFail($id);
        $title = "Assignment";
        $subjects = Subject::all();
        $kelas = Kelas::all();
        $users = User::where('role', 'Lecturer')->get();

        return view("assignment/edit", compact('assignment', 'title', 'subjects', 'users', 'kelas'));
    }

    public function update(Request $request, int $id) {

        $request->validate([
            'subject_id'=>'required|exists:subject,id',
            'user_id'=>'required|exists:user,id',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        try {
            DB::beginTransaction();

            $assignment = Assignment::findOrFail($id);
            $assignment->update([
                'subject_id' => $request->subject_id,
                'user_id' => $request->user_id,
                'kelas_id' => $request->kelas_id,
            ]);

            DB::commit();

            return redirect('assignment/create')->with('status', 'Assignment updated');
        } catch (QueryException $e) {
            DB::rollBack();

            $errorMessage = "The combination of subject and user and class must be unique.";

            return redirect('assignment/create')->with('error', $errorMessage);
        }
    }

    public function destroy(int $id) {
        if (Auth::check()) {
            $assignment = Assignment::findOrFail($id);
            $assignment->delete();
            return redirect('assignment/')->with('status', 'Assignment deleted');
        } else {
            return redirect('/login')->with('loginError', 'Please login to delete assignment');
        }
    }
}
