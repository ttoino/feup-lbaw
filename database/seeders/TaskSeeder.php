<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Project;
use App\Models\Task;

use Illuminate\Database\Eloquent\Factories\Sequence;

class TaskSeeder extends Seeder {

    const MIN_TASKS_PER_GROUP = 1;
    const MAX_TASKS_PER_GROUP = 5;

    const MIN_ASSIGNEES_PER_TASK = 0;
    const MAX_ASSIGNEES_PER_TASK = 2;

    const MIN_TAGS_PER_TASK = 0;
    const MAX_TAGS_PER_TASK = 3;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        
        $faker = \Faker\Factory::create();

        $projects = Project::all();

        foreach ($projects as $project) {

            $projectMembers = $project->users;
            $projectTaskGroups = $project->taskGroups;
            $projectTags = $project->tags;

            $assigneeGenerator = fn () => $projectMembers->random($faker->numberBetween(
                TaskSeeder::MIN_ASSIGNEES_PER_TASK, 
                min(TaskSeeder::MAX_ASSIGNEES_PER_TASK, $projectMembers->count())
            ));

            $tagGenerator = fn () => $projectTags->random($faker->numberBetween(
                TaskSeeder::MIN_TAGS_PER_TASK, 
                min(TaskSeeder::MAX_TAGS_PER_TASK, $projectTags->count())
            ));

            foreach ($projectTaskGroups as $taskGroup) {

                Task::factory()
                    ->count($faker->numberBetween(
                        TaskSeeder::MIN_TASKS_PER_GROUP,
                        TaskSeeder::MAX_TASKS_PER_GROUP
                    ))
                    ->withPosition()
                    ->hasAttached(
                        $assigneeGenerator(),
                        [],
                        'assignees'
                    )
                    ->hasAttached(
                        $tagGenerator(),
                        [],
                        'tags'
                    )
                    ->for($taskGroup)
                    ->create();

            }
        }
    }
}
