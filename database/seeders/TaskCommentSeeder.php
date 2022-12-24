<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\TaskComment;
use App\Models\Task;
use App\Models\Project;

class TaskCommentSeeder extends Seeder {
    
    const MIN_COMMENTS_PER_TASK = 0;
    const MAX_COMMENTS_PER_TASK = 5;
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $faker = \Faker\Factory::create();

        $projects = Project::all();

        foreach ($projects as $project) {

            $tasks = $project->tasks;
            $projectMembers = $project->users;
            
            foreach ($tasks as $task) {
                TaskComment::factory()
                    ->count($faker->numberBetween(TaskCommentSeeder::MIN_COMMENTS_PER_TASK, TaskCommentSeeder::MAX_COMMENTS_PER_TASK))
                    ->for($task)
                    ->withAuthors($projectMembers)
                    ->create();
            }
        }
    }
}
