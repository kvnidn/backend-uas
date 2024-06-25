<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\Schedule;
use App\Models\KeyLending;

class VerifyKeyLendingEnd implements Rule
{
    protected $scheduleId;
    protected $password;

    public function __construct($scheduleId, $password)
    {
        $this->scheduleId = $scheduleId;
        $this->password = $password;
    }

    public function passes($attribute, $value)
    {
        // Find the schedule and ensure it exists
        $schedule = Schedule::findOrFail($this->scheduleId);

        // Retrieve the user associated with the schedule
        $user = $schedule->assignment->user;

        // Check if the password matches using Hash::check
        if (Hash::check($this->password, $user->password)) {
            // Find the KeyLending entry
            $keyLending = KeyLending::where('schedule_id', $this->scheduleId)->first();

            if ($keyLending) {
                // Update the end_time of the KeyLending entry
                $keyLending->end_time = now();
                $keyLending->save();
                return true;
            } else {
                // KeyLending entry not found
                return false;
            }
        } else {
            // Password verification failed
            return false;
        }
    }

    public function message()
    {
        return 'Password verification failed or Key lending entry not found.';
    }
}