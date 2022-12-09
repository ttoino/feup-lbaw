<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $path = 'resources/sql';

        $this->command->info('Creating schema!');
        DB::unprepared(file_get_contents("$path/schema.sql"));

        $this->command->info('Adding indexes!');
        DB::unprepared(file_get_contents("$path/indexes.sql"));

        $this->command->info('Adding triggers!');
        DB::unprepared(file_get_contents("$path/triggers.sql"));

        $this->command->info('Populating database!');
        DB::unprepared(file_get_contents("$path/populate.sql"));

        $this->command->info('Database seeded!');
    }
}