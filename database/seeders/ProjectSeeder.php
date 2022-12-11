<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Database\Eloquent\Factories\Sequence;

use App\Models\Project;
use App\Models\User;

class ProjectSeeder extends Seeder {

    const NORMAL_COUNT = 30;
    const ARCHIVED_COUNT = 5;

    const COORDINATOR_COUNT = 10;

    const PROJECT_MEMBER_RATIO = 2/5;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $faker = \Faker\Factory::create();

        $coordinators = User::factory()
            ->state(new Sequence(
                fn ($sequence) => ['email' => "coordinator$sequence->index@example.com"]
            ))->count(ProjectSeeder::COORDINATOR_COUNT)
            ->create();

        $normalUsers = User::where('is_admin', '<>', 'true')->get()->diff($coordinators);

        $memberRatio = $normalUsers->count() * ProjectSeeder::PROJECT_MEMBER_RATIO;

        Project::factory()
            ->count(ProjectSeeder::NORMAL_COUNT)
            ->state(function ($attributes) use ($coordinators) {
                return ['coordinator_id' => $coordinators->random()];
            })
            ->hasAttached(
                $normalUsers->random($memberRatio),
                ['is_favorite' => $faker->boolean()],
                'users'
            )
            ->create();

        Project::factory()
            ->count(ProjectSeeder::ARCHIVED_COUNT)
            ->archived()
            ->state(function ($attributes) use ($coordinators) {
                return ['coordinator_id' => $coordinators->random()];
            })
            ->hasAttached(
                $normalUsers->random($memberRatio),
                ['is_favorite' => $faker->boolean()],
                'users'
            )
            ->create();
    }
}
