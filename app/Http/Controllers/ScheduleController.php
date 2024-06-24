<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Room;
use App\Models\User;
use App\Models\Subject;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    // Controller for /schedule (Schedule Page)
    public function index(Request $request)
    {
        $allRoom = Room::orderBy('room_number')->get();
        $allLecturer = User::whereIn('role', ['Lecturer', 'Assistant'])->orderBy('name')->get();
        $allSubject = Subject::orderBy('name')->get();
        // Fetch all schedules
        $query = Schedule::join('assignment', 'schedule.assignment_id', '=', 'assignment.id')
        ->join('kelas', 'assignment.kelas_id', '=', 'kelas.id')
        ->join('subject', 'kelas.subject_id', '=', 'subject.id')
        ->orderByRaw("CASE WHEN EXTRACT(DOW FROM date::date) = 0 THEN 7 ELSE EXTRACT(DOW FROM date::date) END")
        ->orderBy('start_time')
        ->orderBy('subject.name')
        ->select('schedule.*');

        // Filter by room
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->input('room_id'));
        }

        // Filter by user
        if ($request->filled('lecturer_id')) {
            $query->whereHas('assignment', function ($q) use ($request) {
                $q->where('user_id', $request->input('lecturer_id'));
            });
        }

        // Filter by subject
        if ($request->filled('subject_id')) {
            $query->whereHas('assignment.kelas.subject', function ($q) use ($request) {
                $q->where('id', $request->input('subject_id'));
            });
        }

        // Filter by day of week
        if ($request->filled('day_of_week')) {
            $selectedDayOfWeek = $request->input('day_of_week');
            // Convert day name to the corresponding numeric value (1 for Monday, 2 for Tuesday, etc.)
            $dayOfWeekNumeric = date('N', strtotime($selectedDayOfWeek));

            // Adjust for Sunday to make sure it includes schedules for Sunday
            if ($dayOfWeekNumeric == 7) {
                $query->whereRaw("EXTRACT(DOW FROM date::date) IN (0, 7)");
            } else {
                $query->whereRaw("EXTRACT(DOW FROM date::date) = $dayOfWeekNumeric");
            }
        }

        $schedules = $query->get();
        // Schedule grouping by day of week
        $groupedSchedules = $schedules->groupBy(function ($item) {
            $dayOfWeek = Carbon::parse($item->date)->dayOfWeek;
            return $dayOfWeek . '|' . $item->start_time . '|' . $item->end_time . '|' . $item->assignment_id . '|' . $item->room_id;
        });

        $allAssignments = Assignment::with('kelas.subject')
            ->get()
            ->sortBy(function($assignment) {
                return $assignment->kelas->subject->name;
            });
        $rooms = Room::orderBy('room_number')->get();

        $allSchedule = Schedule::all();

        $title = 'Schedule';

        return view('schedule.index', compact('schedules', 'title', 'groupedSchedules', 'allRoom', 'allLecturer', 'allSubject', 'allAssignments', 'rooms', 'allSchedule'));
    }

    // Controller for /view 
    // Schedule shown by date, will show today's date by default
    public function view(Request $request, $date = null) {
        $allRoom = Room::orderBy('room_number')->get();
        $selectedRoom = $request->input('room_id', null);
        $selectedDate = $date ?: $request->input('date', today()->toDateString());

        $prevDate = Schedule::whereDate('date', '<', $selectedDate)->max('date');
        $nextDate = Schedule::whereDate('date', '>', $selectedDate)->min('date');
        $earliestDate = Schedule::min('date');
        $furthestDate = Schedule::max('date');

        $title = "View";

        $query = Schedule::whereDate('date', $selectedDate)->orderBy('start_time')
        ->join('assignment', 'schedule.assignment_id', '=', 'assignment.id')
        ->join('kelas', 'assignment.kelas_id', '=', 'kelas.id')
        ->join('subject', 'kelas.subject_id', '=', 'subject.id')
        ->orderByRaw("CASE WHEN EXTRACT(DOW FROM date::date) = 0 THEN 7 ELSE EXTRACT(DOW FROM date::date) END")
        ->orderBy('start_time')
        ->orderBy('subject.name')
        ->select('schedule.*');

        if ($selectedRoom) {
            $query->where('room_id', $selectedRoom);
        }
        $schedules = $query->get();

        return view('schedule.view', compact('title', 'schedules', 'selectedDate', 'prevDate', 'nextDate', 'earliestDate', 'furthestDate', 'allRoom', 'selectedRoom'));
    }

    // Controller to provide create schedule form data
    public function create() {
        $title = 'Schedule';
        $assignments = Assignment::with('kelas.subject')
            ->get()
            ->sortBy(function($assignment) {
                return $assignment->kelas->subject->name;
            });
        $rooms = Room::orderBy('room_number')->get();
        return view('schedule/create', [
            'title'=> $title,
            'assignments'=> $assignments,
            'rooms'=> $rooms,
        ]);
    }

    // Controller to save created schedule after checking for conflict with existing schedule
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
                ->join('assignment', 'schedule.assignment_id', '=', 'assignment.id')
                ->where('schedule.date', $date->toDateString())
                ->where(function($query) use ($request) {
                    $query->where('schedule.room_id', $request->room_id)
                          ->orWhere(function($query) use ($request) {
                              $query->where('assignment.user_id', function($query) use ($request) {
                                        $query->select('user_id')
                                              ->from('assignment')
                                              ->where('id', $request->assignment_id);
                                    })
                                    ->orWhere('assignment.kelas_id', function($query) use ($request) {
                                        $query->select('kelas_id')
                                              ->from('assignment')
                                              ->where('id', $request->assignment_id);
                                    });
                          });
                })
                ->where(function($query) use ($request) {
                    $query->where(function($query) use ($request) {
                            $query->where('schedule.start_time', '>=', $request->start_time)
                                  ->where('schedule.start_time', '<', $request->end_time);
                        })
                        ->orWhere(function($query) use ($request) {
                            $query->where('schedule.end_time', '>', $request->start_time)
                                  ->where('schedule.end_time', '<=', $request->end_time);
                        })
                        ->orWhere(function($query) use ($request) {
                            $query->where('schedule.start_time', '<', $request->start_time)
                                  ->where('schedule.end_time', '>', $request->end_time);
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

        return redirect('schedule/')->with('status', 'Schedule created');
    }


    // Controller to provide edit schedule form data
    public function edit(int $id) {
        $schedule = Schedule::findOrFail($id);
        $title = 'Schedule';
        $assignments = Assignment::with('kelas.subject')
            ->get()
            ->sortBy(function($assignment) {
                return $assignment->kelas->subject->name;
            });
        $rooms = Room::orderBy('room_number')->get();
        return view('schedule/edit', compact('schedule','title','assignments','rooms'));
    }

    // Controller to save changes made to existing schedule data
    public function update(Request $request, int $id) {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'assignment_id' => 'required|exists:assignment,id',
            'room_id' => 'required|exists:room,id',
        ]);

        $conflict = \DB::table('schedule')
            ->join('assignment', 'schedule.assignment_id', '=', 'assignment.id')
            ->where('schedule.date', $request->date)
            ->where('schedule.id', '!=', $id)
            ->where(function($query) use ($request) {
                $query->where('schedule.room_id', $request->room_id)
                      ->orWhere(function($query) use ($request) {
                          $query->where('assignment.user_id', function($query) use ($request) {
                                    $query->select('user_id')
                                          ->from('assignment')
                                          ->where('id', $request->assignment_id);
                                })
                                ->orWhere('assignment.kelas_id', function($query) use ($request) {
                                    $query->select('kelas_id')
                                          ->from('assignment')
                                          ->where('id', $request->assignment_id);
                                });
                      });
            })
            ->where(function($query) use ($request) {
                $query->whereBetween('schedule.start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('schedule.end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function($query) use ($request) {
                          $query->where('schedule.start_time', '<=', $request->start_time)
                                ->where('schedule.end_time', '>=', $request->end_time);
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

        return redirect()->back()->with('status', 'Schedule updated');
    }

    // Controller to delete schedule data
    public function destroy(int $id) {
        if (Auth::check()) {
            $schedule = Schedule::findOrFail($id);
            $schedule->delete();
            return redirect('schedule/')->with('status','Schedule deleted');
        } else {
            return redirect('/login')->with('loginError', 'Please login to delete schedule');
        }
    }

    // Controller to provide batch edit schedule form data
    public function batchEdit(Request $request) {
        $ids = explode(',', $request->query('ids'));
        $schedules = Schedule::whereIn('id', $ids)->get();
        $assignments = Assignment::with('kelas.subject')
            ->get()
            ->sortBy(function($assignment) {
                return $assignment->kelas->subject->name;
            });
        $rooms = Room::all();
        $title = 'Schedule';
        return view('schedule/batch_edit', compact('title', 'schedules', 'assignments', 'rooms'));
    }

    // Controller to save changes made to several schedule data after checking for conflict with existing schedule
    public function batchUpdate(Request $request) {
        $ids = explode(',', $request->query('ids'));
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

                // Get the lecturer and class IDs from the assignment
                $assignment = Assignment::findOrFail($schedule->assignment_id);
                $user_id = $assignment->user_id;
                $kelas_id = $assignment->kelas_id;

                // Check for conflicts
                $conflict = \DB::table('schedule')
                    ->join('assignment', 'schedule.assignment_id', '=', 'assignment.id')
                    ->where('schedule.date', $schedule->date)
                    ->where('schedule.id', '!=', $id)
                    ->where(function($query) use ($schedule, $user_id, $kelas_id) {
                        $query->where('schedule.room_id', $schedule->room_id)
                              ->orWhere(function($query) use ($user_id, $kelas_id) {
                                  $query->where('assignment.user_id', $user_id)
                                        ->orWhere('assignment.kelas_id', $kelas_id);
                              });
                    })
                    ->where(function($query) use ($schedule) {
                        $query->whereBetween('schedule.start_time', [$schedule->start_time, $schedule->end_time])
                              ->orWhereBetween('schedule.end_time', [$schedule->start_time, $schedule->end_time])
                              ->orWhere(function($query) use ($schedule) {
                                  $query->where('schedule.start_time', '<=', $schedule->start_time)
                                        ->where('schedule.end_time', '>=', $schedule->end_time);
                              });
                    })
                    ->exists();

                if ($conflict) {
                    return redirect()->back()->withErrors(['time' => 'The selected time slot conflicts with an existing schedule.']);
                }

                $schedule->save();
            }
        }

        return redirect()->back()->with('status', 'Schedules updated successfully.');
    }

    // Controller to delete schedule data in batch
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

        return redirect()->back()->with('status', 'Schedules deleted successfully.');
    }
}
