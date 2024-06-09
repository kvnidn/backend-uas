<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Room;
use App\Models\Schedule;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    //
    public function index() {
        $schedule = Schedule::orderBy('date')->orderBy('start_time')->get();
        $groupedSchedules = $schedule->groupBy(function ($item) {
            return $item->start_time . '|' . $item->end_time . '|' . $item->assignment_id . '|' . $item->room_id;
        });
        $title = 'Schedule';
        return view('schedule/index', compact('schedule','title', 'groupedSchedules'));
    }
    
    public function view($date = null) {
        $now = new DateTime();
        $currentDayOfWeek = (int)$now->format('N'); // Get the current day of the week (1 for Monday, 7 for Sunday)
    
        // Determine the initial selected date based on the current day of the week
        if ($currentDayOfWeek >= 6) { // Saturday (6) or Sunday (7)
            $now->modify('next Monday');
        }
        $selectedDate = $now->format('Y-m-d');
    
        if ($date) {
            $selectedDate = $date;
        }
    
        // Calculate previous and next dates based on the selected date
        $prevDate = (clone $now)->modify('-1 day');
        $nextDate = (clone $now)->modify('+1 day');
    
        // Fetch the first schedule date before the selected date as the previous date
        $prevDate = Schedule::whereDate('date', '<', $selectedDate)->max('date');
    
        // Fetch the first schedule date after the selected date as the next date
        $nextDate = Schedule::whereDate('date', '>', $selectedDate)->min('date');

        $earliestDate = Schedule::min('date');

        $furthestDate = Schedule::max('date');
    
        $title = "View";
        
        // Fetch schedules for the selected date
        $schedules = Schedule::whereDate('date', $selectedDate)->orderBy('start_time')->get();
    
        return view('schedule.view', compact('title', 'schedules', 'selectedDate', 'prevDate', 'nextDate', 'earliestDate', 'furthestDate'));
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
            'repeat' => 'nullable|integer|min:0|max:52',
            'interval' => 'nullable|string|in:weekly,biweekly',
        ]);
    
        $repeats = $request->input('repeat', 0);
        $intervalDays = 7;
    
        $schedules = [];
        for ($i = 0; $i <= $repeats; $i++) {
            $date = \Carbon\Carbon::parse($request->date)->addDays($i * $intervalDays);
    
            $conflict = \DB::table('schedule')
                ->where('room_id', $request->room_id)
                ->where('date', $date->toDateString())
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
                return redirect()->back()->withErrors(['time' => 'The selected time slot conflicts with an existing schedule on ' . $date->toDateString() . '.']);
            }
    
            $schedules[] = [
                'date' => $date->toDateString(),
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'assignment_id' => $request->assignment_id,
                'room_id' => $request->room_id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    
        Schedule::insert($schedules);
    
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
    
    public function batchEdit(Request $request) {
        $ids = explode(',', $request->query('ids'));
        $schedules = Schedule::whereIn('id', $ids)->get();
        $assignments = Assignment::all();
        $rooms = Room::all();
        $title = 'Schedule';
        return view('schedule/batch_edit', compact('title', 'schedules', 'assignments', 'rooms'));
    }
    
    
    public function batchUpdate(Request $request) {
        $ids = $request->input('ids');
        $dates = $request->input('dates'); // Retrieve dates from the request
    
        foreach ($ids as $index => $id) {
            $schedule = Schedule::find($id);
            if ($schedule) {
                // Use the date from the request for each schedule
                $schedule->date = $dates[$index];
                $schedule->start_time = $request->input('start_time');
                $schedule->end_time = $request->input('end_time');
                $schedule->assignment_id = $request->input('assignment_id');
                $schedule->room_id = $request->input('room_id');
    
                // Check for conflicts
                $conflict = Schedule::where('room_id', $schedule->room_id)
                                    ->where('date', $schedule->date)
                                    ->where('id', '!=', $id)
                                    ->where(function($query) use ($schedule) {
                                        $query->whereBetween('start_time', [$schedule->start_time, $schedule->end_time])
                                              ->orWhereBetween('end_time', [$schedule->start_time, $schedule->end_time])
                                              ->orWhere(function($query) use ($schedule) {
                                                  $query->where('start_time', '<=', $schedule->start_time)
                                                        ->where('end_time', '>=', $schedule->end_time);
                                              });
                                    })
                                    ->exists();
    
                if ($conflict) {
                    return redirect()->back()->withErrors(['time' => 'The selected time slot conflicts with an existing schedule.']);
                }
    
                $schedule->save();
            }
        }
    
        return redirect()->back()->with('success', 'Schedules updated successfully.');
    }
    
    public function batchDestroy(Request $request) {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect('/login')->with('loginError', 'Please login to delete schedules.');
        }
    
        // Retrieve the IDs from the request
        $ids = $request->input('ids');
    
        // Check if IDs are provided
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No schedules selected for deletion.');
        }
    
        // Convert the IDs string into an array
        $idsArray = explode(',', $ids);
    
        // Find and delete schedules with the provided IDs
        Schedule::whereIn('id', $idsArray)->delete();
    
        return redirect()->back()->with('success', 'Schedules deleted successfully.');
    }
    
    
    
    
}
