<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    function getNonAdmin(int $pageSize): LengthAwarePaginator
    {
        return $this->model->where('admin', 0)->paginate($pageSize);
    }

    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function findById(int $id): ?User
    {
        return $this->model->find($id);
    }

    function getWithTasks(): Collection
    {
        return $this->model
            ->select(['id', 'name'])
            ->withCount([
                'tasks as pending_tasks_count' => fn($q) => $q->where('status', 'pending'),
                'tasks as completed_tasks_count' => fn($q) => $q->where('status', 'completed'),
            ])
            ->where('admin', 0)
            ->get();
    }
}
