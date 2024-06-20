<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeyLendingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Open the file
        $file = fopen("database\SeedData\key_lending_data.csv", 'r');

        // Process the CSV file row by row
        while (($row = fgetcsv($file)) !== FALSE) {
            DB::table('key_lending')->insert([
                'id' => $row[0],
                'schedule_id' => $row[1],
                'start_time' => $row[2],
                'end_time' => $row[3],
                'created_at' => $row[4],
                'updated_at' => $row[5],
            ]);
        }

        // Close the file
        fclose($file);

        // Get the maximum ID
         $maxId = DB::table('key_lending')->max('id');

        // Set the next sequence value for PostgreSQL
         DB::statement('SELECT setval(\'key_lending_id_seq\', ' . ($maxId + 1) . ');');
    }
}
