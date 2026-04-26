<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreProjectRequest;
use App\Http\Requests\V1\UpdateProjectRequest;
use App\Http\Resources\V1\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

/**
 * @OA\Tag(
 *     name="projects",
 *     description="Project management endpoints"
 * )
 */
class ProjectController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/projects",
     *     tags={"projects"},
     *     summary="List projects",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of projects",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Project")
     *         )
     *     )
     * )
     */
    public function index(): JsonResource
    {
        Gate::authorize('viewAny', Project::class);

        $projects = auth()->user()->isAdmin()
            ? Project::with('owner')->paginate()//for admin
            : Project::ownedByUser()->paginate();//for other users

        return ProjectResource::collection($projects);
    }

    /**
     * @OA\Post(
     *     path="/api/projects",
     *     tags={"projects"},
     *     summary="Create a project",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Project Alpha")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Project created",
     *         @OA\JsonContent(ref="#/components/schemas/Project")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        Gate::authorize('create', Project::class);

        $data = $request->validated();

        $data['owner_id'] = auth()->id();

        $project = Project::create($data);

        return response()->json(
            [
                'success' => true,
                'data' => new ProjectResource($project->load('owner'))
            ], Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Get(
     *     path="/api/projects/{id}",
     *     tags={"projects"},
     *     summary="Show a project",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Project details",
     *         @OA\JsonContent(ref="#/components/schemas/Project")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Project not found"
     *     )
     * )
     */
    public function show(Project $project): JsonResource
    {
        Gate::authorize('view', $project);

        $project->load(['owner']);

        return new ProjectResource($project);
    }

    /**
     * @OA\Put(
     *     path="/api/projects/{id}",
     *     tags={"projects"},
     *     summary="Update a project",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Project Beta")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Project updated",
     *         @OA\JsonContent(ref="#/components/schemas/Project")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Project not found"
     *     )
     * )
     */
    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        Gate::authorize('update', $project);

        $project->update($request->validated());

        return response()->json(
            [
                'success' => true,
                'message' => 'Project updated successfully',
                'data' => new ProjectResource($project),
            ], 200
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/projects/{id}",
     *     tags={"projects"},
     *     summary="Delete a project",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Project deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Project not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Project has folders and cannot be deleted"
     *     )
     * )
     */
    public function destroy(Project $project): JsonResponse
    {
        Gate::authorize('delete', $project);

        if ($project->folders()->exists()) {

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Project has some folders'
                ], 422
            );

        }

        $project->delete();

        return response()->json(
            [
                'success' => true,
                'message' => 'Project deleted successfully'
            ], 200
        );
    }
}
