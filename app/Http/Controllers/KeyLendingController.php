<?php

namespace App\Http\Controllers;

use App\Models\KeyLending;
use App\Models\Schedule;
use App\Models\Room;
use App\Rules\VerifyKeyLending;
use App\Rules\VerifyKeyLendingEnd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class KeyLendingController extends Controller
{
    // Not yet used
    public function index() {
        $keyLending = KeyLending::orderBy('id')->get();
        $title = 'KeyLending';
        return view('key-lending/index', compact('keyLending', 'title'));
    }

    // Not yet used
    public function create() {
        $title = 'KeyLending';
        $schedule = Schedule::all();
        return view('key-lending/create', [
            'title'=>$title,
            'schedule'=>$schedule
        ]);
    }

    // Not yet used
    public function store(Request $request) {
        $request->validate([
            'schedule_id'=>'required|exists:schedule,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
        ]);

        KeyLending::create([
            'schedule_id' => $request->schedule_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect('key-lending/create')->with('status', 'Key lending created');
    }

    // Not yet used
    public function edit(int $id) {
        $keyLending = KeyLending::findOrFail($id);
        $title = "KeyLending";

        return view("assignment/edit", compact('keyLending', 'title'));
    }

    // Not yet used
    public function update(Request $request, int $id) {

        $request->validate([
            'schedule_id'=>'required|exists:schedule,id',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
        ]);

        $keyLending = KeyLending::findOrFail($id);
        $keyLending->update([
            'schedule_id' => $request->schedule_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect('key-lending/')->with('status', 'Key lending updated');
    }

    // Not yet used
    public function destroy(int $id) {
        if (Auth::check()) {
            $keyLending = KeyLending::findOrFail($id);
            $keyLending->delete();
            return redirect('key-lending/')->with('status', 'Key lending deleted');
        } else {
            return redirect('/login')->with('loginError', 'Please login to delete assignment');
        }
    }


    public function showVerifyForm($id)
    {
        $schedule = Schedule::findOrFail($id);
        return view('key-lending.verify', compact('schedule'));
    }

    public function verifyAndUpdateStart(Request $request, $id)
    {
        if (auth()->check() && auth()->user()->role == 'Admin') {
            $this->createKeyLending($id);
            return redirect()->back()->with('status', 'Key lending start time saved.');
        }

        $validator = Validator::make($request->all(),[
            'password' => 'required',
        ]);

        $password = $request->input('password');

        $validator->after(function ($validator) use ($id, $password) {
            $uniqueLendRule = new VerifyKeyLending($id, $password);
            if (!$uniqueLendRule->passes(null, $password)) {
                $validator->errors()->add('password', $uniqueLendRule->message());
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator, 'keyLending')
                        ->withInput();
        }

        return redirect()->back()->with('status', 'Key lending start time saved.');
    }

    public function verifyAndUpdateEnd(Request $request, $id)
    {
        if (auth()->check() && auth()->user()->role == 'Admin') {
            $this->createKeyLendingEnd($id);
            return redirect()->back()->with('status', 'Key lending end time saved.');
        }

        $validator = Validator::make($request->all(),[
            'password' => 'required',
        ]);

        $password = $request->input('password');

        $validator->after(function ($validator) use ($id, $password) {
            $uniqueLendRule = new VerifyKeyLendingEnd($id, $password);
            if (!$uniqueLendRule->passes(null, $password)) {
                $validator->errors()->add('password', $uniqueLendRule->message());
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator, 'keyLending')
                        ->withInput();
        }

        return redirect()->back()->with('status', 'Key lending end time saved.');
    }

    public function viewToday(Request $request)
    {
        $selectedRoomId = $request->input('room_id');
        $selectedDate = now()->format('Y-m-d');
        $title = "KeyLending";

        // Fetch all rooms
        $allRoom = Room::orderBy('room_number')->get();

        // Fetch key lending records for today
        $query = Schedule::whereDate('date', $selectedDate)->orderBy('start_time')
        ->join('assignment', 'schedule.assignment_id', '=', 'assignment.id')
        ->join('kelas', 'assignment.kelas_id', '=', 'kelas.id')
        ->join('subject', 'kelas.subject_id', '=', 'subject.id')
        ->orderBy('subject.name')
        ->select('schedule.*');

        if ($selectedRoomId) {
            $query->where('room_id', $selectedRoomId);
        }

        $schedule = $query
        ->get();
        $keyLendings = KeyLending::whereDate('created_at', $selectedDate)->orderBy('start_time')->get();

        return view('key-lending.view', compact('title', 'keyLendings', 'selectedDate', 'schedule', 'selectedRoomId', 'allRoom'));
    }

    // Only available for Admin
    private function createKeyLending($id)
    {
        $schedule = Schedule::findOrFail($id);
        KeyLending::create([
            'schedule_id' => $schedule->id,
            'start_time' => now(),
            'end_time' => now(),
        ]);
    }

    private function createKeyLendingEnd($id)
    {
        $schedule = Schedule::findOrFail($id);
        KeyLending::where('schedule_id', $schedule->id)->update([
            'end_time' => now(),
        ]);
    }
}
