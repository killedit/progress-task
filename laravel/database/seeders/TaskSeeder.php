<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'test@example.com')->first();

        if (! $user) {
            $this->command->warn('No test user found. Run UserSeeder first.');
            return;
        }

        $tasks = [
            [
                'title' => 'Understand Task Requirements.',
                'description' => 'Creating CRUD application for assigning tasks will require a default web page where all tasks will be listed. Then to differenciate users they will have to login and will be able to create/edit/delete tasks.',
                'assigned_to' => $user->id,
                'created_by' => $user->id,
                'is_completed' => true,
                'due_date' => Carbon::now()->addDays(2),
            ],
            [
                'title' => 'Define Project Structure.',
                'description' => 'Laravel will handle the backend and REST API, VueJs will be the frontend framework, MySQL will store the data and Nginx will serve the application.',
                'assigned_to' => $user->id,
                'created_by' => $user->id,
                'is_completed' => false,
                'due_date' => Carbon::now()->addDays(3),
            ],
            [
                'title' => 'Database migrations, seeders.',
                'description' => 'Add some content so the application is not empty at initial setup. Also it will help with testing the application functionality.',
                'assigned_to' => $user->id,
                'created_by' => $user->id,
                'is_completed' => false,
                'due_date' => Carbon::now()->addDays(1),
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
