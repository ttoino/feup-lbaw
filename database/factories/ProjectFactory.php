<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;

use App\Models\Project;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory {

    const EDITED_PROJECT_PERCENTAGE = 0.6;
    const PROJECTS_WITH_DESCRIPTION_PERCENTAGE = 0.7;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() {
        return [
            'name' => $this->faker->company,
            'description' => $this->faker->optional(ProjectFactory::PROJECTS_WITH_DESCRIPTION_PERCENTAGE)->paragraph,
            'creation_date' => $this->faker->dateTimeThisYear('-5 month'),
            'last_modification_date' => function ($attributes) {
                $this->faker->optional(ProjectFactory::EDITED_PROJECT_PERCENTAGE)->dateTimeBetween($attributes['creation_date'], 'now');
            },
            'archived' => false,
        ];
    }

    public function archived() {
        return $this->state(function (array $attributes) {
            return [
                'archived' => true
            ];
        });
    }

    protected $model = Project::class;
}
