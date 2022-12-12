<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\ThreadComment;
use App\Models\Thread;
use App\Models\Project;

class ThreadCommentSeeder extends Seeder {
    
    const MIN_COMMENTS_PER_THREAD = 0;
    const MAX_COMMENTS_PER_THREAD = 5;
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $faker = \Faker\Factory::create();

        $projects = Project::all();

        foreach ($projects as $project) {

            $threads = $project->threads;
            $projectMembers = $project->users;
            
            foreach ($threads as $thread) {
                ThreadComment::factory()
                    ->count($faker->numberBetween(ThreadCommentSeeder::MIN_COMMENTS_PER_THREAD, ThreadCommentSeeder::MAX_COMMENTS_PER_THREAD))
                    ->for($thread)
                    ->withAuthors($projectMembers)
                    ->create();
            }
        }
    }
}
