<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use App\Repositories\TaskRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected TaskService $taskService;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user, ['*']);
        $this->taskService = new TaskService(new TaskRepository(new Task()));
    }

    public function it_can_create_a_task()
    {
        $task = $this->taskService->create([
            'title' => 'Sample Task',
            'description' => 'This is a test task',
            'priority' => 'medium'
        ]);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertDatabaseHas('tasks', ['title' => 'Sample Task']);
    }

    public function it_can_mark_task_as_complete()
    {
        $task = Task::factory()->create(['status' => 'pending']);

        $completed = $this->taskService->markAsComplete($task);

        $this->assertEquals('completed', $completed->status);
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'completed']);
    }

    public function it_can_delete_a_task()
    {
        $task = Task::factory()->create();

        $result = $this->taskService->destroy($task);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function it_can_reorder_tasks()
    {
        $task1 = Task::factory()->create(['order' => 1]);
        $task2 = Task::factory()->create(['order' => 2]);

        $this->taskService->reorder([
            ['id' => $task1->id, 'order' => 2],
            ['id' => $task2->id, 'order' => 1],
        ]);

        $this->assertDatabaseHas('tasks', ['id' => $task1->id, 'order' => 2]);
        $this->assertDatabaseHas('tasks', ['id' => $task2->id, 'order' => 1]);
    }
}
