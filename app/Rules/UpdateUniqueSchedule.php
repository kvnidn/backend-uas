<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UpdateUniqueSchedule implements Rule
{
    protected $request;
    protected $scheduleId;

    public function __construct($request, $scheduleId)
    {
        $this->request = $request;
        $this->scheduleId = $scheduleId;
    }

    public function passes($attribute, $value)
    {
        $conflict = DB::table('schedule')
            ->join('assignment', 'schedule.assignment_id', '=', 'assignment.id')
            ->where('schedule.date', $this->request->date)
            ->where('schedule.id', '!=', $this->scheduleId)
            ->where(function($query) {
                $query->where('schedule.room_id', $this->request->room_id)
                      ->orWhere(function($query) {
                          $query->where('assignment.user_id', function($query) {
                                    $query->select('user_id')
                                          ->from('assignment')
                                          ->where('id', $this->request->assignment_id);
                                  })
                                  ->orWhere('assignment.kelas_id', function($query) {
                                    $query->select('kelas_id')
                                          ->from('assignment')
                                          ->where('id', $this->request->assignment_id);
                                  });
                      });
            })
            ->where(function($query) {
                $query->whereBetween('schedule.start_time', [$this->request->start_time, $this->request->end_time])
                      ->orWhereBetween('schedule.end_time', [$this->request->start_time, $this->request->end_time])
                      ->orWhere(function($query) {
                          $query->where('schedule.start_time', '<=', $this->request->start_time)
                                ->where('schedule.end_time', '>=', $this->request->end_time);
                      });
            })
            ->exists();

        return !$conflict;
    }

    public function message()
    {
        return 'The selected time slot conflicts with an existing schedule.';
    }
}
