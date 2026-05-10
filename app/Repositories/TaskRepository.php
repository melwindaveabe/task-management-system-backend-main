<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class TaskRepository
{
    protected $model;

    function __construct(Task $task)
    {
        $this->model = $task;
    }

    function get(): Collection
    {
        $key = 'task_of_' . auth()->user()->id;
        return Cache::remember($key, 3600, function () {
            return $this->model->where('user_id', auth()->user()->id)->orderBy('order')->get();
        });
    }

    function getWithUser(array $request): LengthAwarePaginator
    {
        return $this->model
            ->with(['user:id,name'])
            ->where(function ($q) use ($request) {
                if (array_key_exists('search', $request)) {
                    $search = $request['search'];
                    $q->where(function ($q) use ($search) {
                        $q->where('title', 'like', '%' . $search . '%')
                            ->orWhere('description', 'like', '%' . $search . '%');
                    });
                }

                if (array_key_exists('status', $request))
                    $q->status($request['status']);
                if (array_key_exists('priority', $request))
                    $q->priority($request['priority']);
            })
            ->orderBy('order')
            ->paginate($request['page_size'] ?? 10);
    }

    function getByUserId(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->orderBy('order')->get();
    }

    function create(array $data): Task
    {
        return $this->model->create($data);
    }

    function getMaxOrder(): int
    {
        return $this->model->where('user_id', auth()->user()->id)->max('order') ?? 0;
    }

    function findById(int $id): ?Task
    {
        return $this->model->find($id);
    }

    function findByIdAndUserId(int $id): ?Task
    {
        return $this->model->where('user_id', auth()->user()->id)->where('id', $id)->first();
    }

    function updateStatus(Task $task, string $status): Task
    {
        $task->update(['status' => $status]);
        return $task;
    }

    function updateOrder(Task $task, int $order): Task
    {
        $task->update(['order' => $order]);
        return $task;
    }

    function destroy(Task $task): bool
    {
        return $task->delete();
    }

    function reorder(array $tasksOrder): void
    {
        foreach ($tasksOrder as $item) {
            $task = $this->findById($item['id']);
            if ($task) {
                $task->update(['order' => $item['order']]);
            }
        }
    }

    function filterByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }

    function filterByPriority(string $priority): Collection
    {
        return $this->model->where('priority', $priority)->get();
    }
}
