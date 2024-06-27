<?php

namespace App\Http\Controllers;

use App\Models\KeyLending;
use App\Models\Room;
use App\Models\Schedule;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Controller for /home (Home Page)
    public function index(Request $request)
    {
        $allRoom = Room::orderBy('room_number')->get();
        $selectedRoom = $request->input('room_id', null);
        $selectedDate = today()->toDateString();
        $currentTime = now()->toTimeString(); // Get current time

        $title = "Home";

        $query = Schedule::whereDate('date', $selectedDate)
            // Filter schedules starting after current time
            ->where('start_time', '>', $currentTime) 
            ->join('assignment', 'schedule.assignment_id', '=', 'assignment.id')
            ->join('kelas', 'assignment.kelas_id', '=', 'kelas.id')
            ->join('subject', 'kelas.subject_id', '=', 'subject.id')
            ->orderByRaw("CASE WHEN EXTRACT(DOW FROM date::date) = 0 THEN 7 ELSE EXTRACT(DOW FROM date::date) END")
            ->orderBy('start_time')
            ->orderBy('subject.name')
            ->select('schedule.*');

        $query2 = KeyLending::whereDate('key_lending.created_at', $selectedDate)
        ->whereColumn('key_lending.start_time', '=', 'key_lending.end_time')
        ->join('schedule', 'key_lending.schedule_id', '=', 'schedule.id')
        ->join('assignment', 'schedule.assignment_id', '=', 'assignment.id')
        ->join('kelas', 'assignment.kelas_id', '=', 'kelas.id')
        ->join('subject', 'kelas.subject_id', '=', 'subject.id')
        ->orderBy('start_time')
        ->orderBy('subject.name')
        ->select('key_lending.*');

        if ($selectedRoom) {
            $query->where('room_id', $selectedRoom);
            $query2->where('room_id', $selectedRoom);
        }
        $schedules = $query->get();
        $keyLendings = $query2->get();

        return view('home', compact('title', 'schedules', 'selectedDate', 'allRoom', 'selectedRoom', 'keyLendings', 'selectedRoom'));
    }
    
}