<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Task;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement([Task::TODO,Task::INPROGRESS,Task::DONE]),
            'priority' => $this->faker->randomElement(['low','medium','high']),
            'due_date_time' => $this->faker->dateTimeBetween('now', '+1 month'),
            'assigned_user' => User::factory(), 
        ];
    }
}
