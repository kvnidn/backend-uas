<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateUniqueSchedule implements Rule
{
    protected $request;
    protected $repeats;
    protected $intervalDays;

    public function __construct($request)
    {
        $this->request = $request;
        $this->repeats = $request->input('repeat', 0);
        $this->intervalDays = $request->input('interval') === 'biweekly' ? 14 : 7;
    }

    public function passes($attribute, $value)
    {
        for ($i = 0; $i <= $this->repeats; $i++) {
            $date = Carbon::parse($this->request->date)->addDays($i * $this->intervalDays);

            $conflict = DB::table('schedule')
                ->join('assignment', 'schedule.assignment_id', '=', 'assignment.id')
                ->where('schedule.date', $date->toDateString())
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
                    $query->where(function($query) {
                            $query->where('schedule.start_time', '>=', $this->request->start_time)
                                  ->where('schedule.start_time', '<', $this->request->end_time);
                        })
                        ->orWhere(function($query) {
                            $query->where('schedule.end_time', '>', $this->request->start_time)
                                  ->where('schedule.end_time', '<=', $this->request->end_time);
                        })
                        ->orWhere(function($query) {
                            $query->where('schedule.start_time', '<', $this->request->start_time)
                                  ->where('schedule.end_time', '>', $this->request->end_time);
                        });
                })
                ->exists();

            if ($conflict) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return 'The selected time slot conflicts with an existing schedule.';
    }
}
