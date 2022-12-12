<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\TaskGroup;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskGroup>
 */
class TaskGroupFactory extends Factory {
    
    const TASK_GROUPS_WITH_DESCRIPTION_PERCENTAGE = 0.4;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() {
        return [
            'name' => $this->faker->sentence,
            'description' => $this->faker->optional(TaskGroupFactory::TASK_GROUPS_WITH_DESCRIPTION_PERCENTAGE)->paragraph,
        ];
    }

    public function withPosition() {
        return $this->sequence(
            fn ($sequence) => ['position' => $sequence->index + 1]
        );
    }

    protected $model = TaskGroup::class;
}
