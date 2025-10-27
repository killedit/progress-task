<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'assigned_to' => User::factory(),
            'created_by' => User::factory(),
            'is_completed' => false,
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
        ];
    }
}
