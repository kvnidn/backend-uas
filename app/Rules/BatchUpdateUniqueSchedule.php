<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BatchUpdateUniqueSchedule implements Rule
{
    protected $schedule;
    protected $user_id;
    protected $kelas_id;

    public function __construct($schedule, $user_id, $kelas_id)
    {
        $this->schedule = $schedule;
        $this->user_id = $user_id;
        $this->kelas_id = $kelas_id;
    }

    public function passes($attribute, $value)
    {
        $conflict = DB::table('schedule')
            ->join('assignment', 'schedule.assignment_id', '=', 'assignment.id')
            ->where('schedule.date', $this->schedule['date'])
            ->where('schedule.id', '!=', $this->schedule['id'])
            ->where(function($query) {
                $query->where('schedule.room_id', $this->schedule['room_id'])
                      ->orWhere(function($query) {
                          $query->where('assignment.user_id', $this->user_id)
                                ->orWhere('assignment.kelas_id', $this->kelas_id);
                      });
            })
            ->where(function($query) {
                $query->whereBetween('schedule.start_time', [$this->schedule['start_time'], $this->schedule['end_time']])
                      ->orWhereBetween('schedule.end_time', [$this->schedule['start_time'], $this->schedule['end_time']])
                      ->orWhere(function($query) {
                          $query->where('schedule.start_time', '<=', $this->schedule['start_time'])
                                ->where('schedule.end_time', '>=', $this->schedule['end_time']);
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
