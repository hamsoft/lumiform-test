<?php

namespace Database\Factories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Form default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            Question::TITLE => fake()->text(),

            Question::NOTES_ALLOWED => fake()->boolean(),
            Question::PHOTOS_ALLOWED => fake()->boolean(),
            Question::ISSUES_ALLOWED => fake()->boolean(),

            Question::NEGATIVE => fake()->boolean(),
            Question::RESPONDED => fake()->boolean(),
            Question::REQUIRED => fake()->boolean(),

            Question::RESPONSE_TYPE => fake()->randomElement(Question::RESPONSE_TYPES),
        ];
    }
}
