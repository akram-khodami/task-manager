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
use App\Events\AssignedTask;

/**
 * @OA\Tag(
 *     name="tasks",
 *     description="Task management endpoints"
 * )
 */
class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     tags={"tasks"},
     *     summary="List tasks",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="project_id", in="query", required=false, @OA\Schema(type="integer"), description="Filter by project ID"),
     *     @OA\Parameter(name="status", in="query", required=false, @OA\Schema(type="string"), description="Filter by status"),
     *     @OA\Parameter(name="priority", in="query", required=false, @OA\Schema(type="string"), description="Filter by priority"),
     *     @OA\Parameter(name="assigned_to", in="query", required=false, @OA\Schema(type="integer"), description="Filter by assigned user ID"),
     *     @OA\Parameter(name="created_by", in="query", required=false, @OA\Schema(type="integer"), description="Filter by creator user ID"),
     *     @OA\Parameter(name="search", in="query", required=false, @OA\Schema(type="string"), description="Search in title or description"),
     *     @OA\Response(
     *         response=200,
     *         description="List of tasks",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Task"))
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/tasks",
     *     tags={"tasks"},
     *     summary="Create a task",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","folder_id","status","priority"},
     *             @OA\Property(property="title", type="string", example="Implement login"),
     *             @OA\Property(property="description", type="string", example="Implement the login functionality for the app"),
     *             @OA\Property(property="folder_id", type="integer", example=1),
     *             @OA\Property(property="status", type="string", example="pending"),
     *             @OA\Property(property="priority", type="string", example="high"),
     *             @OA\Property(property="due_date", type="string", format="date", example="2025-06-10"),
     *             @OA\Property(property="assigned_to", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();

        $data['created_by'] = auth()->id();

        $task = Task::create($data);

        if ($task->assigned_to) {

            event(new AssignedTask($task));
        }

        return response()->json(
            [
                'success' => true,
                'message' => 'Task created successfully',
                'data' => new TaskResource($task->load(['folder', 'creator', 'assignedUser']))
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     tags={"tasks"},
     *     summary="Show a task",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Task details",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     */
    public function show(Task $task)
    {
        Gate::authorize('view', $task);

        $task->load(['folder', 'creator', 'assignedUser']);

        return response()->json(new TaskResource($task));
    }

    /**
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     tags={"tasks"},
     *     summary="Update a task",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","status","priority"},
     *             @OA\Property(property="title", type="string", example="Implement login"),
     *             @OA\Property(property="description", type="string", example="Implement the login functionality for the app"),
     *             @OA\Property(property="status", type="string", example="pending"),
     *             @OA\Property(property="priority", type="string", example="high"),
     *             @OA\Property(property="due_date", type="string", format="date", example="2025-06-10"),
     *             @OA\Property(property="assigned_to", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task updated",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        Gate::authorize('update', $task);

        $task->update($request->validated());

        return response()->json(
            [
                'success' => true,
                'message' => 'Task updated successfully',
                'data' => new TaskResource($task),
            ],
            200
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/tasks/{id}",
     *     tags={"tasks"},
     *     summary="Delete a task",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Task deleted"
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Task has attachments, cannot be deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     */
    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);

        if ($task->attachments()->exists()) {

            return response()->json(
                [
                    'success' => false,
                    'message' => 'This task has some attachments'
                ],
                Response::HTTP_CONFLICT
            );
        }

        $task->delete();

        return response()->json(
            [
                'success' => true,
                'message' => 'Task deleted successfully'
            ],
            200
        );
    }
}
