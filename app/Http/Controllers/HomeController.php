<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Room;
use App\Models\User;
use App\Models\Subject;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        if ($selectedRoom) {
            $query->where('room_id', $selectedRoom);
        }
        $schedules = $query->get();

        return view('home', compact('title', 'schedules', 'selectedDate', 'allRoom', 'selectedRoom'));
    }
    
}