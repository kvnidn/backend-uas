<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Open the file
        $file = fopen("database\SeedData\kelas_data.csv", 'r');

        // Process the CSV file row by row
        while (($row = fgetcsv($file)) !== FALSE) {
            DB::table('kelas')->insert([
                'id' => $row[0],
                'prodi' => $row[1],
                'subject_id' => $row[2],
                'class' => $row[3],
                'created_at' => $row[4],
                'updated_at' => $row[5],
            ]);
        }

        // Close the file
        fclose($file);

        // Get the maximum ID
         $maxId = DB::table('kelas')->max('id');

        // Set the next sequence value for PostgreSQL
         DB::statement('SELECT setval(\'kelas_id_seq\', ' . ($maxId + 1) . ');');
    }
}
