<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\Schedule;
use App\Models\KeyLending;

class VerifyKeyLending implements Rule
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
            try {
                // Attempt to create KeyLending record
                KeyLending::create([
                    'schedule_id' => $schedule->id,
                    'start_time' => now(),
                    'end_time' => now(),
                ]);
                return true;
            } catch (\Exception $e) {
                // Failed to create KeyLending record
                return false;
            }
        } else {
            // Password verification failed
            return false;
        }
    }

    public function message()
    {
        return 'Password verification failed or failed to save key lending.';
    }
}
