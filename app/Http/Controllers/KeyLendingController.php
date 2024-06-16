<?php

namespace App\Http\Controllers;

use App\Models\KeyLending;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

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
        $password = $request->input('password');

        // Find the schedule and ensure it exists
        $schedule = Schedule::findOrFail($id);

        // Retrieve the user associated with the schedule
        $user = $schedule->assignment->user;

        
        
        // Check if the password matches using Hash::check
        if (Hash::check($password, $user->password)) {
            try {
                KeyLending::create([
                    'schedule_id' => $schedule->id,
                    'start_time' => now(),
                    'end_time' => now(),
                ]);
                return redirect()->back()->with('status', 'Key lending start time saved.');
            } catch (QueryException $e) {
                return redirect()->back()->with('error', 'Failed to save key lending.');
            }
        } else {
            // Password verification failed
            return redirect()->back()->with('error', 'Password verification failed.');
        }
    }



    public function verifyAndUpdateEnd(Request $request, $id)
    {
        $password = $request->input('password');

        // Find the schedule and ensure it exists
        $schedule = Schedule::findOrFail($id);

        // Retrieve the user associated with the schedule
        $user = $schedule->assignment->user;

        if (Hash::check($password, $user->password)) {
            $keyLending = KeyLending::where('schedule_id', $id)->first();
            if ($keyLending) {
                $keyLending->end_time = now();
                $keyLending->save();
                return redirect()->back()->with('status', 'Key lending end time saved.');
            } else {
                return redirect()->back()->with('error', 'Key lending entry not found.');
            }
        } else {
            return redirect()->back()->with('error', 'Password verification failed.');
        }
    }

    public function viewToday()
    {
        $selectedDate = now()->format('Y-m-d');
        $title = "KeyLending";

        // Fetch key lending records for today
        $schedule = Schedule::whereDate('date', $selectedDate)->orderBy('start_time')->get();
        $keyLendings = KeyLending::whereDate('created_at', $selectedDate)->orderBy('start_time')->get();

        return view('key-lending.view', compact('title', 'keyLendings', 'selectedDate', 'schedule'));
    }
}
