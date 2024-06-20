<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    //
    public function index(Request $request)
{
    $query = Assignment::join('kelas', 'assignment.kelas_id', '=', 'kelas.id')
    ->join('user', 'assignment.user_id', '=', 'user.id')
    ->join('subject', 'kelas.subject_id', '=', 'subject.id')
    ->orderBy('subject.name')
    ->orderBy('kelas.class')
    ->orderBy('user.name', 'desc')
    ->select('assignment.*');

    $allLecturer = User::whereIn('role', ['Lecturer', 'Assistant'])->orderBy('name')->get();
    $allSubject = Subject::all();

    // Filter by subject_id if provided in the request
    if ($request->filled('subject_id')) {
        $subjectId = $request->input('subject_id');
        $query->whereHas('kelas', function ($q) use ($subjectId) {
            $q->where('subject_id', $subjectId);
        });
    }

    if ($request->filled('lecturer_id')) {
        $lecturerId = $request->input('lecturer_id');
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('user_id', $request->input('lecturer_id'));
        });
    }

    // Get the filtered assignments
    $assignments = $query->get();

    // Title for the view
    $title = 'Assignment';

    // Pass data to the view
    return view('assignment.index', compact('assignments', 'title', 'allLecturer', 'allSubject'));
}

    

    public function create() {
        $title = 'Assignment';
        $users = User::whereIn('role', ['Lecturer', 'Assistant'])->orderBy('name')->get();
        $kelas = Kelas::all();
        return view('assignment/create', [
            'title'=>$title,
            'users'=>$users,
            'kelas'=>$kelas,
        ]);
    }

    public function store(Request $request) {


        $request->validate([
            'user_id'=>'required|exists:user,id',
            'kelas_id'=>'required|exists:kelas,id',
        ]);

        try {
            DB::beginTransaction();

            Assignment::create([
                'user_id' => $request->user_id,
                'kelas_id' => $request->kelas_id,
            ]);

            DB::commit();

            return redirect('assignment/create')->with('status', 'Assignment created');
        } catch (QueryException $e) {
            DB::rollBack();

            $errorMessage = "The combination of user and class must be unique.";

            return redirect('assignment/create')->with('error', $errorMessage);
        }
    }

    public function edit(int $id) {
        $assignment = Assignment::findOrFail($id);
        $title = "Assignment";
        $kelas = Kelas::all();
        $users = User::whereIn('role', ['Lecturer', 'Assistant'])->orderBy('name')->get();

        return view("assignment/edit", compact('assignment', 'title', 'users', 'kelas'));
    }

    public function update(Request $request, int $id) {

        $request->validate([
            'user_id'=>'required|exists:user,id',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        try {
            DB::beginTransaction();

            $assignment = Assignment::findOrFail($id);
            $assignment->update([
                'user_id' => $request->user_id,
                'kelas_id' => $request->kelas_id,
            ]);

            DB::commit();

            return redirect('assignment/')->with('status', 'Assignment updated');
        } catch (QueryException $e) {
            DB::rollBack();

            $errorMessage = "The combination of user and class must be unique.";

            return redirect()->back()->with('error', $errorMessage);
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
