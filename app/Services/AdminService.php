<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminService
{
    protected $userRepository, $taskRepository;

    public function __construct(UserRepository $userRepository, TaskRepository $taskRepository)
    {
        $this->userRepository = $userRepository;
        $this->taskRepository = $taskRepository;
    }

    public function getUsers(int $pageSize): LengthAwarePaginator
    {
        return $this->userRepository->getNonAdmin($pageSize);
    }

    public function dashboard(): array
    {
        $data = $this->userRepository->getWithTasks()->toArray();

        return [
            'users' => array_column($data, 'name'),
            'pending' => array_column($data, 'pending_tasks_count'),
            'completed' => array_column($data, 'completed_tasks_count'),
        ];
    }

    function getTasks(array $request): LengthAwarePaginator
    {
        return $this->taskRepository->getWithUser($request);
    }

    function getTaskById(int $id): ?Task
    {
        return $this->taskRepository->findById($id);
    }

    function getTaskByUserId(int $userId): Collection
    {
        return $this->taskRepository->getByUserId($userId);
    }

    function deleteTask(Task $task): bool
    {
        return $this->taskRepository->destroy($task);
    }
}
