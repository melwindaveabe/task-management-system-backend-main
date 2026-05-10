<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user, ['*']);
    }

    /**
     * A basic test example.
     */
    public function it_can_list_tasks()
    {
        Task::factory()->count(3)->for($this->user)->create();

        $response = $this->getJson('/api/task');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function it_can_create_a_task()
    {
        $payload = [
            'title' => 'New Task',
            'description' => 'Feature test description',
            'status' => 'pending',
            'priority' => 'medium',
            'order' => 1,
        ];

        $response = $this->postJson('/api/task', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'New Task']);

        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task',
            'user_id' => $this->user->id,
        ]);
    }

    public function it_can_mark_as_complete()
    {
        $task = Task::factory()->for($this->user)->create([
            'status' => 'pending',
        ]);

        $response = $this->patchJson("/api/task/mark-as-complete/{$task->id}", [
            'status' => 'completed',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => 'completed']);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'completed',
        ]);
    }

    public function it_can_delete_a_task()
    {
        $task = Task::factory()->for($this->user)->create();

        $response = $this->deleteJson("/api/task/{$task->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
}
