<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class KelasController extends Controller
{
    //
    public function index() {
        $class = Kelas::join('subject', 'kelas.subject_id', '=', 'subject.id')
                ->orderBy('subject.name')
                ->orderBy('class')
                ->select('kelas.*')  // Select all columns from 'kelas' table
                ->get();
        $subjects = Subject::all();
        $title = 'Class';
        return view('class/index', compact('class', 'title', 'subjects'));
    }

    public function create() {
        $title = 'Class';
        $subjects = Subject::all();
        return view('class/create', [
            'title'=>$title,
            'subjects'=>$subjects,
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'prodi'=> 'required|string',
            'subject_id'=>'required|exists:subject,id',
            'class'=>'required|string',
        ]);

        try {
            DB::beginTransaction();
            Kelas::create([
                'prodi' => $request->prodi,
                'subject_id' => $request->subject_id,
                'class' => $request->class,
            ]);

            DB::commit();

            return redirect('class/')->with('status', 'Class created');
        } catch (QueryException $e) {
            DB::rollback();

            $errorMessage = "The combination of Subject, and Class must be unique.";

        return redirect('class/')->with('error', $errorMessage);
        }
    }

    public function edit(int $id) {
        $class = Kelas::findOrFail($id);
        $subjects = Subject::all();
        $title = "Class";

        return view('class/edit', compact('class', 'title', 'subjects'));
    }

    public function update(Request $request, int $id) {
        $request->validate([
            'prodi'=> 'required|string',
            'subject_id'=>'required|exists:subject,id',
            'class'=>'required|string',
        ]);

        try {
            DB::beginTransaction();
            $class = Kelas::findOrFail($id);
            $class->update([
                'prodi' => $request->prodi,
                'subject_id' => $request->subject_id,
                'class' => $request->class,
            ]);
            DB::commit();

            return redirect('class/')->with('status', 'Class updated');
        } catch (QueryException $e) {
            DB::rollBack();
            $errorMessage = "The combination of Subject, and Class must be unique.";
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    public function destroy(int $id) {
        if (Auth::check()) {
            $class = Kelas::findOrFail($id);
            $class->delete();
            return redirect('class/')->with('status', 'Class deleted');
        } else {
            return redirect('/login')->with('loginError', 'Please login to delete class');
        }
    }
}
