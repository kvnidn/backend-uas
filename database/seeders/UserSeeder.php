<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Open the file
        $file = fopen("database\SeedData\user_data.csv", 'r');

        // Process the CSV file row by row
        while (($row = fgetcsv($file)) !== FALSE) {
            DB::table('user')->insert([
                'id' => $row[0],
                'name' => $row[1],
                'email' => $row[2],
                'password' => $row[3],
                'role' => $row[4],
                'remember_token' => $row[5],
                'created_at' => $row[6],
                'updated_at' => $row[7],
            ]);
        }

        // Close the file
        fclose($file);

        // Get the maximum ID
         $maxId = DB::table('user')->max('id');

        // Set the next sequence value for PostgreSQL
         DB::statement('SELECT setval(\'user_id_seq\', ' . ($maxId + 1) . ');');

    }
}
