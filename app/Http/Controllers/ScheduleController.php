<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Room;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    //
    public function index() {
        $schedule = Schedule::orderBy('date')->orderBy('start_time')->get();
        $title = 'Schedule';
        return view('schedule/index', compact('schedule','title'));
    }

    public function create() {
        $title = 'Schedule';
        $assignments = Assignment::select('assignment.*')
            ->join('subject', 'assignment.subject_id', '=', 'subject.id')
            ->orderBy('subject.name')
            ->get();
        $rooms = Room::orderBy('room_number')->get();
        return view('schedule/create', [
            'title'=> $title,
            'assignments'=> $assignments,
            'rooms'=> $rooms,
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'assignment_id' => 'required|exists:assignment,id',
            'room_id' => 'required|exists:room,id',
        ]);
    
        $conflict = \DB::table('schedule')
        ->where('room_id', $request->room_id)
        ->where('date', $request->date)
        ->where(function($query) use ($request) {
            $query->where(function($query) use ($request) {
                    $query->where('start_time', '>=', $request->start_time)
                            ->where('start_time', '<', $request->end_time);
                })
                ->orWhere(function($query) use ($request) {
                    $query->where('end_time', '>', $request->start_time)
                            ->where('end_time', '<=', $request->end_time);
                })
                ->orWhere(function($query) use ($request) {
                    $query->where('start_time', '<', $request->start_time)
                            ->where('end_time', '>', $request->end_time);
                });
        })
        ->exists();

    
        if ($conflict) {
            return redirect()->back()->withErrors(['time' => 'The selected time slot conflicts with an existing schedule.']);
        }
    
        Schedule::create([
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'assignment_id' => $request->assignment_id,
            'room_id' => $request->room_id,
        ]);
    
        return redirect('schedule/create')->with('status', 'Schedule created');
    }

    public function edit(int $id) {
        $schedule = Schedule::findOrFail($id);
        $title = 'Schedule';
        $assignments = Assignment::select('assignment.*')
            ->join('subject', 'assignment.subject_id', '=', 'subject.id')
            ->orderBy('subject.name')
            ->get();
        $rooms = Room::orderBy('room_number')->get();
        return view('schedule/edit', compact('schedule','title','assignments','rooms'));
    }
    
    public function update(Request $request, int $id) {
        $request->validate([
            'date' => 'required|date',
            // Validate start time only if it's different from the existing value
            'start_time' => $request->start_time != $request->start_time ? 'required|date_format:H:i' : '',
            // Validate end time only if it's different from the existing value
            'end_time' => $request->end_time != $request->end_time ? 'required|date_format:H:i|after:start_time' : '',
            'assignment_id' => 'required|exists:assignment,id',
            'room_id' => 'required|exists:room,id',
        ]);
    
        $conflict = \DB::table('schedule')
            ->where('room_id', $request->room_id)
            ->where('date', $request->date)
            ->where('id', '!=', $id)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function($query) use ($request) {
                          $query->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->exists();
    
        if ($conflict) {
            return redirect()->back()->withErrors(['time' => 'The selected time slot conflicts with an existing schedule.']);
        }
    
        $schedule = Schedule::findOrFail($id);
        $schedule->update([
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'assignment_id' => $request->assignment_id,
            'room_id' => $request->room_id,
        ]);
    
        return redirect('schedule')->with('status', 'Schedule updated');
    }

    public function destroy(int $id) {
        if (Auth::check()) {
            $schedule = Schedule::findOrFail($id);
            $schedule->delete();
            return redirect('schedule/')->with('status','Schedule deleted');
        } else {
            return redirect('/login')->with('loginError', 'Please login to delete schedule');
        }
    }
    
}
