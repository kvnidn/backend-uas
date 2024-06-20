<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Open the file
        $file = fopen("database\SeedData\assignment_data.csv", 'r');

        // Process the CSV file row by row
        while (($row = fgetcsv($file)) !== FALSE) {
            DB::table('assignment')->insert([
                'id' => $row[0],
                'user_id' => $row[1],
                'kelas_id' => $row[2],
                'created_at' => $row[3],
                'updated_at' => $row[4],
            ]);
        }

        // Close the file
        fclose($file);

        // Get the maximum ID
         $maxId = DB::table('assignment')->max('id');

        // Set the next sequence value for PostgreSQL
         DB::statement('SELECT setval(\'assignment_id_seq\', ' . ($maxId + 1) . ');');
    }
}
