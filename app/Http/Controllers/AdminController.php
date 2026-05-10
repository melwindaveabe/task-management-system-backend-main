<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController
{
    protected $service;

    function __construct(AdminService $service)
    {
        $this->service = $service;
    }

    function users(Request $request): JsonResponse
    {
        $users = $this->service->getUsers($request['page_size'] ?? 10);
        return response()->json(['data' => $users]);
    }

    function dashboard(): JsonResponse
    {
        $users = $this->service->dashboard();
        return response()->json(['data' => $users]);
    }

    function tasks(Request $request): JsonResponse
    {
        $tasks = $this->service->getTasks($request->toArray());
        return response()->json(['data' => $tasks]);
    }

    public function getTasksPerUser(int $userId): JsonResponse
    {
        $tasks = $this->service->getTaskByUserId($userId);
        return response()->json(['data' => $tasks]);
    }

    public function deleteTask(int $id): JsonResponse
    {
        $task = $this->service->getTaskById($id);
        if (!$task) {
            return response()->json(['success' => false, 'message' => 'Task not found'], 404);
        }
        $this->service->deleteTask($task);
        return response()->json(['message' => 'Task deleted']);
    }
}
