<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Room;
use App\Models\User;
use App\Models\Subject;
use App\Models\Schedule;
use App\Rules\UpdateUniqueSchedule;
use App\Rules\CreateUniqueSchedule;
use App\Rules\BatchUpdateUniqueSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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
        $validator = Validator::make($request->all(),[
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'assignment_id' => 'required|exists:assignment,id',
            'room_id' => 'required|exists:room,id',
            'repeat' => 'nullable|integer|min:0|max:52',
            'interval' => 'nullable|string|in:weekly,biweekly',
        ]);

        // Add the custom validation rule after the built-in rules
        $validator->after(function ($validator) use ($request) {
            $uniqueScheduleRule = new CreateUniqueSchedule($request);
            if (!$uniqueScheduleRule->passes(null, null)) {
                $validator->errors()->add('schedule', $uniqueScheduleRule->message());
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator, 'createSchedule')
                        ->withInput();
        }

        $repeats = $request->input('repeat', 0);
        $intervalDays = 7;

        $schedules = [];
        for ($i = 0; $i <= $repeats; $i++) {
            $date = \Carbon\Carbon::parse($request->date)->addDays($i * $intervalDays);

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
        $validator = Validator::make($request->all(),[
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'assignment_id' => 'required|exists:assignment,id',
            'room_id' => 'required|exists:room,id',
        ]);

        $validator->after(function ($validator) use ($request, $id) {
            $uniqueScheduleRule = new UpdateUniqueSchedule($request, $id);
            if (!$uniqueScheduleRule->passes(null, null)) {
                $validator->errors()->add('schedule', $uniqueScheduleRule->message());
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator, 'editSchedule')
                        ->withInput();
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
    
        $data = [];
        foreach ($ids as $index => $id) {
            $data[] = [
                'id' => $id,
                'date' => $dates[$index],
                'start_time' => $request->input('start_time'),
                'end_time' => $request->input('end_time'),
                'assignment_id' => $request->input('assignment_id'),
                'room_id' => $request->input('room_id'),
            ];
        }
    
        foreach ($data as $index => $scheduleData) {
            $schedule = Schedule::find($scheduleData['id']);
            if ($schedule) {
                $assignment = Assignment::findOrFail($scheduleData['assignment_id']);
                $user_id = $assignment->user_id;
                $kelas_id = $assignment->kelas_id;
    
                $validator = Validator::make($scheduleData, [
                    'date' => 'required|date',
                    'start_time' => 'required',
                    'end_time' => 'required|after:start_time',
                    'assignment_id' => 'required|exists:assignment,id',
                    'room_id' => 'required|exists:room,id',
                ]);

                $validator->after(function ($validator) use ($scheduleData, $user_id, $kelas_id) {
                    $uniqueScheduleRule = new BatchUpdateUniqueSchedule($scheduleData, $user_id, $kelas_id);
                    if (!$uniqueScheduleRule->passes(null, null)) {
                        $validator->errors()->add('schedule', $uniqueScheduleRule->message());
                    }
                });
    
                if ($validator->fails()) {
                    return redirect()->back()
                                ->withErrors($validator, 'editScheduleBatch')
                                ->withInput();
                }
            }
        }
    
        foreach ($data as $scheduleData) {
            $schedule = Schedule::find($scheduleData['id']);
            if ($schedule) {
                $schedule->date = $scheduleData['date'];
                $schedule->start_time = $scheduleData['start_time'];
                $schedule->end_time = $scheduleData['end_time'];
                $schedule->assignment_id = $scheduleData['assignment_id'];
                $schedule->room_id = $scheduleData['room_id'];
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
