<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Open the file
        $file = fopen("database\SeedData\\room_data.csv", 'r');

        // Process the CSV file row by row
        while (($row = fgetcsv($file)) !== FALSE) {
            DB::table('room')->insert([
                'id' => $row[0],
                'room_number' => $row[1],
                'created_at' => $row[2],
                'updated_at' => $row[3],
            ]);
        }

        // Close the file
        fclose($file);

        // Get the maximum ID
         $maxId = DB::table('room')->max('id');

        // Set the next sequence value for PostgreSQL
         DB::statement('SELECT setval(\'room_id_seq\', ' . ($maxId + 1) . ');');
    }
}
