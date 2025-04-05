<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Experience>
 */
class ExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'position' => 'Developer',
            'company' => 'PT Contoh',
            'location' => 'Bandung',
            'status' => 'Internship',
            'your_skills' => 'PHP, Laravel',
            'start_date' => now()->subMonths(6),
            'end_date' => now(),
            'users_id' => \App\Models\User::factory(),
        ];
    }
}
