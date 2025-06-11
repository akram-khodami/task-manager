<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreTaskRequest;
use App\Http\Requests\V1\UpdateTaskRequest;
use App\Http\Resources\V1\TaskResource;
use App\Models\Task;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     */
    public function index()
    {
        $tasks = Task::with([
            'folder:id,name',
            'project:id,name',
            'creator:id,name,email',
            'assignedUser:id,name,email'
        ])
            ->whereHas('folder', function ($query) {
                $query->when(request('project_id'), fn ($q, $projectId) => $q->where('project_id', $projectId))
                    ->whereHas('project', fn ($q) => $q->where('owner_id', auth()->id()));
            })
            ->when(request('status'), function ($query, $status) {
                $query->where('status', $status);
            })
            ->when(request('priority'), function ($query, $priority) {
                $query->where('priority', $priority);
            })
            ->when(request('assigned_to'), function ($query, $assignedTo) {
                $query->where('assigned_to', $assignedTo);
            })
            ->when(request('created_by'), function ($query, $createdBy) {
                $query->where('created_by', $createdBy);
            })
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate();

        return response()->json(TaskResource::collection($tasks));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();

        $data['created_by'] = auth()->id();

        $task = Task::create($data);

        return response()->json(new TaskResource($task->load(['folder', 'creator', 'assignedUser'])), Response::HTTP_CREATED);
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        $task->load(['folder', 'creator', 'assignedUser']);

        return response()->json(new TaskResource($task));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        Gate::authorize('update', $task);

        $task->update($request->validated());

        return response()->json(new TaskResource($task));
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);

        if ($task->attachments()->exists()) {

            return response()->json([
                'message' => 'این تسک دارای پیوست است و قابل حذف نیست'
            ], Response::HTTP_CONFLICT);

        }

        $task->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
