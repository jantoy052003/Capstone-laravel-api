<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'    => fake()->randomElement(User::pluck('id')),
            'task_title' => fake()->realText($maxNbChars = 30),
            'task_body'  => fake()->realText($maxNbChars = 100),
            'task_date'  => fake()->date($format = 'Y-m-d', $max = 'now'),
            'image'      => '1682508964.jpg',
        ];
    }
}
