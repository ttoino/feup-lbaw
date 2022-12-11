<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() {
        return [
            'title' => $this->faker->text(50),
            'description' => $this->faker->optional(ProjectFactory::PROJECTS_WITH_DESCRIPTION_PERCENTAGE)->sentence,
            'color' => hexdec($this->faker->hexColor)
        ];
    }
}
