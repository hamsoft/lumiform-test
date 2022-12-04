<?php

namespace Database\Factories;

use App\Models\Form;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Form>
 */
class FormFactory extends Factory
{
    /**
     * Form default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            Form::TITLE => fake()->text(),
            Form::DESCRIPTION => fake()->text(),
        ];
    }
}
