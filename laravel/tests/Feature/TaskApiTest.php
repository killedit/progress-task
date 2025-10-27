<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_get_tasks_public()
    {
        Task::factory()->count(3)->create();
        $response = $this->getJson('/api/tasks');
        $response->assertStatus(200)
                 ->assertJsonStructure(['data', 'current_page','last_page']);
    }

    public function test_create_task_authenticated()
    {
        $payload = [
            'title' => 'New Task',
            'description' => 'Test description',
            'assigned_to' => $this->user->id,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/tasks', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'New Task']);

        $this->assertDatabaseHas('tasks', ['title' => 'New Task']);
    }

    public function test_update_task_authenticated()
    {
        $task = Task::factory()->create(['created_by' => $this->user->id]);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->putJson("/api/tasks/{$task->id}", [
                             'title' => 'Updated Title'
                         ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', ['id'=>$task->id, 'title'=>'Updated Title']);
    }

    public function test_delete_task_authenticated()
    {
        $task = Task::factory()->create(['created_by' => $this->user->id]);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('tasks', ['id'=>$task->id]);
    }
}
