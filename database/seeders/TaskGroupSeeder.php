<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\TaskGroup;
use App\Models\Project;

use Illuminate\Database\Eloquent\Factories\Sequence;

class TaskGroupSeeder extends Seeder {

    const MIN_TASK_GROUPS = 2;
    const MAX_TASK_GROUPS = 5;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $faker = fake();

        $projects = Project::all();

        foreach ($projects as $project) {
            TaskGroup::factory()
                ->count($faker->numberBetween(TaskGroupSeeder::MIN_TASK_GROUPS, TaskGroupSeeder::MAX_TASK_GROUPS))
                ->withPosition()
                ->for($project)
                ->create();
        }
    }
}
