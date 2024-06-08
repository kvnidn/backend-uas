<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class KelasController extends Controller
{
    //
    public function index() {
        $class = Kelas::orderBy('id')->get();
        $title = 'Class';
        return view('class/index', compact('class', 'title'));
    }

    public function create() {
        $title = 'Class';
        return view('class/create', [
            'title'=>$title,
        ]);
    }

    public function store(Request $request) {
        $request->validate([
           'prodi'=>'required|string',
           'year'=>'required|integer',
           'class'=>'required|string',
        ]);

        try {
            DB::beginTransaction();
            Kelas::create([
                'prodi' => $request->prodi,
                'year' => $request->year,
                'class' => $request->class,
            ]);

            DB::commit();

            return redirect('class/create')->with('status', 'Class created');
        } catch (QueryException $e) {
            DB::rollback();

            $errorMessage = "The combination of Prodi, Year, and Class must be unique.";

        return redirect('class/create')->with('error', $errorMessage);
        }
    }

    public function edit(int $id) {
        $class = Kelas::findOrFail($id);
        $title = "Class";

        return view('class/edit', compact('class', 'title'));
    }

    public function update(Request $request, int $id) {
        $request->validate([
           'prodi'=>'required|string',
           'year'=>'required|integer',
           'class'=>'required|string',
        ]);

        try {
            DB::beginTransaction();
            $class = Kelas::findOrFail($id);
            $class->update([
                'prodi' => $request->prodi,
                'year' => $request->year,
                'class' => $request->class,
            ]);
            DB::commit();

            return redirect('class/')->with('status', 'Class updated');
        } catch (QueryException $e) {
            DB::rollBack();
            $errorMessage = "The combination of Prodi, Year, and Class must be unique.";
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
