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
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Run UserSeeder first.');
            return;
        }

        $tasks = [
            [
                'title' => 'Understand Task Requirements.',
                'description' => 'Creating CRUD application for assigning tasks will require a default web page where all tasks will be listed. Then to differentiate users they will have to login and will be able to create/edit/delete/complete tasks.',
                'is_completed' => true,
                'due_date' => Carbon::now()->addDays(2),
            ],
            [
                'title' => 'Define Project Structure.',
                'description' => 'Laravel will handle the backend, VueJs the frontend, MySQL will store the data and Nginx will serve the application.',
                'is_completed' => false,
                'due_date' => Carbon::now()->addDays(3),
            ],
            [
                'title' => 'Database migrations, seeders.',
                'description' => 'Add some content so the application is not empty at initial setup. Also it will help with testing the application functionality.',
                'is_completed' => false,
                'due_date' => Carbon::now()->addDays(1),
            ],
            [
                'title' => 'Implement the REST API resources.',
                'description' => 'Allow only logged in users to add/edit/complete/detele tasks. Create postman collection and environment for easy testing.',
                'is_completed' => false,
                'due_date' => Carbon::now()->addDays(5),
            ],
            [
                'title' => 'Update the documentation.',
                'description' => 'Create simple, but full documentation that explains all necessary steps to have running application. Document how to access it, what I wished I have done, but did not have time for.',
                'is_completed' => false,
                'due_date' => Carbon::now()->addDays(7),
            ],
            [
                'title' => 'Create unit/integration test.',
                'description' => 'Unit tests should be done with PHPUnit and code coverage with XDebug. Complete the readme markdown file when ready.',
                'is_completed' => false,
                'due_date' => Carbon::now()->addDays(10),
            ],
        ];

        foreach ($tasks as $task) {
            $createdByUser = $users->random();

            $assignedToUser = $users->random();

            Task::create([
                'title' => $task['title'],
                'description' => $task['description'],
                'assigned_to' => $assignedToUser->id,
                'created_by' => $createdByUser->id,
                'is_completed' => $task['is_completed'],
                'due_date' => $task['due_date'],
            ]);
        }
    }
}
