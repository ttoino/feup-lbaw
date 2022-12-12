<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Tag;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory {

    const TAG_WITH_DESCRIPTION_PERCENTAGE = 0.4;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() {
        return [
            'title' => $this->faker->title,
            'description' => $this->faker->optional(TagFactory::TAG_WITH_DESCRIPTION_PERCENTAGE)->sentence,
            'color' => hexdec($this->faker->hexColor)
        ];
    }

    protected $model = Tag::class;
}
