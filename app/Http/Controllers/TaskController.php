<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Task\CreatePost;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function store(CreatePost $request): JsonResponse
    {
        $task = new Task();
        $task->name = $request->validated('name');
        $task->save();

        return response()->json([
            'id' => $task->id,
            'name' => $task->name,
        ],
        201);
    }

    public function show(int $id): JsonResponse
    {
        $task = Task::query()->findOrFail($id);

        return response()->json([
            'id' => $task->id,
            'name' => $task->name,
        ]);
    }
}
