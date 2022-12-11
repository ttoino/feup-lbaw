<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Tag;

use Illuminate\Database\Seeder;

class TagSeeder extends Seeder {

    const MIN_TAGS_PER_PROJECT = 1;
    CONST MAX_TAGS_PER_PROJECT = 10;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $faker = \Faker\Factory::create();

        $projects = Project::all();

        foreach ($projects as $project) {
            Tag::factory()
                ->count($faker->numberBetween(TagSeeder::MIN_TAGS_PER_PROJECT, TagSeeder::MAX_TAGS_PER_PROJECT))
                ->for($project)
                ->create();
        }
    }
}
