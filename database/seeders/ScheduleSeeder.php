<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Open the file
        $file = fopen("database\SeedData\schedule_data.csv", 'r');

        // Process the CSV file row by row
        while (($row = fgetcsv($file)) !== FALSE) {
            DB::table('schedule')->insert([
                'id' => $row[0],
                'date' => $row[1],
                'start_time' => $row[2],
                'end_time' => $row[3],
                'assignment_id' => $row[4],
                'room_id' => $row[5],
                'created_at' => $row[6],
                'updated_at' => $row[7],
            ]);
        }

        // Close the file
        fclose($file);

        // Get the maximum ID
         $maxId = DB::table('schedule')->max('id');

        // Set the next sequence value for PostgreSQL
         DB::statement('SELECT setval(\'schedule_id_seq\', ' . ($maxId + 1) . ');');
    }
}
