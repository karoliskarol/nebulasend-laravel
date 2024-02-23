<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmailMessage>
 */
class EmailMessageFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'subject' => fake()->title,
            'recipient' => fake()->name(),
            'message' => fake()->paragraph(100),
            'summary' => fake()->paragraph(1),
            'starred' => rand(0, 1),
            'important' => rand(0, 1),
            'sent_at' => fake()->date(),
            'created_at' => fake()->date()
        ];
    }
}
