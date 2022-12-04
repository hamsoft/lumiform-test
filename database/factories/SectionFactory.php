<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Section>
 */
class SectionFactory extends Factory
{
    /**
     * Form default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            Section::TITLE => fake()->text(),
            Section::REPEAT => fake()->boolean(),
            Section::WEIGHT => fake()->randomNumber(),
            Section::REQUIRED => fake()->boolean(),
        ];
    }
}
