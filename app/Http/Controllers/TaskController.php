<?php

namespace App\Http\Controllers;

use App\Http\Requests\InsertTaskRequest;
use App\Http\Requests\ReorderTaskRequest;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskController
{
    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(): JsonResponse
    {
        $tasks = $this->taskService->get();
        return response()->json(['data' => $tasks]);
    }

    public function store(InsertTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->create($request->validated());
        return response()->json(['task' => $task], 201);
    }

    public function markAsComplete(int $id): JsonResponse
    {
        $task = $this->taskService->getById($id);
        if (!$task) {
            return response()->json(['success' => false, 'message' => 'Task not found'], 404);
        }
        if ($task->user_id != auth()->user()->id)
            return response()->json(['success' => false, 'message' => 'Invalid access'], 403);

        $updatedTask = $this->taskService->markAsComplete($task);
        return response()->json(['task' => $updatedTask, 'message' => 'Marked as completed']);
    }

    public function reorder(ReorderTaskRequest $request): JsonResponse
    {
        $this->taskService->reorder($request->validated()['tasks']);
        return response()->json(['message' => 'Tasks reordered']);
    }
}
