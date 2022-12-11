<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\TaskGroup;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskGroup>
 */
class TaskGroupFactory extends Factory {
    
    const TASK_GROUPS_WITH_DESCRIPTION_PERCENTAGE = 0.4;
    
    // These are a non-representative subset of every name that can be used but are good enough, should be increased
    const VALID_TASK_GROUP_NAMES = [
        'TODO',
        'To Do',
        'To-Do',
        'TO_DO',
        'Backlog',
        'BACKLOG',
        'Product Backlog',
        'Iteration Backlog',
        'Milestone',
        'Milestone Backlog',
        'Doing',
        'Current',
        'In Progress',
        'Done',
        'Closed',
        'Finished',
        'Merged',
        'Rejected',
        'In Review',
        'Needs Review',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() {
        return [
            'name' => $this->faker->randomElement(TaskGroupFactory::VALID_TASK_GROUP_NAMES),
            'description' => $this->faker->optional(TaskGroupFactory::TASK_GROUPS_WITH_DESCRIPTION_PERCENTAGE)->paragraph,
        ];
    }

    protected $model = TaskGroup::class;
}
