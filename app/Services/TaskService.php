<?php

namespace App\Services;

use App\Events\TaskUpdated;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class TaskService
{
    protected $taskRepo;

    function __construct(TaskRepository $repo)
    {
        $this->taskRepo = $repo;
    }

    function get(): Collection
    {
        return $this->taskRepo->get();
    }

    function getById(int $id): ?Task
    {
        return $this->taskRepo->findById($id);
    }

    public function create(array $data): Task
    {
        $data['user_id'] = auth()->user()->id;
        $data['status'] = 'pending';
        $data['order'] = $this->taskRepo->getMaxOrder() + 1;

        $task = $this->taskRepo->create($data);
        $this->clearCache();
        event(new TaskUpdated('created'));

        return $task;
    }

    function clearCache()
    {
        Cache::forget('task_of_' . auth()->user()->id);
    }
    function markAsComplete(Task $task): Task
    {
        $this->clearCache();
        event(new TaskUpdated('marked as complete'));
        return $this->taskRepo->updateStatus($task, 'completed');
    }

    function updateStatus(Task $task, string $status): Task
    {
        $this->clearCache();
        return $this->taskRepo->updateStatus($task, $status);
    }

    public function reorder(array $tasksOrder): void
    {
        $this->clearCache();
        foreach ($tasksOrder as $item) {
            $task = $this->taskRepo->findByIdAndUserId($item['id']);
            if ($task) {
                $this->taskRepo->updateOrder($task, $item['order']);
            }
        }
    }
}
