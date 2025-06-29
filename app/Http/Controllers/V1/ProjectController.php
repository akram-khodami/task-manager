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

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     */

    //===note1:use filter
    public function index(): JsonResource
    {
        Gate::authorize('viewAny', Project::class);

        $projects = auth()->user()->isAdmin()
            ? Project::with('owner')->paginate()//for admin
            : Project::ownedByUser()->paginate();//for other users

        return ProjectResource::collection($projects);
    }

    /**
     * Store a newly created project in storage.
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
     * Display the specified project.
     */
    public function show(Project $project): JsonResource
    {
        Gate::authorize('view', $project);

        $project->load(['owner']);

        return new ProjectResource($project);
    }

    /**
     * Update the specified project in storage.
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
     * Remove the specified project from storage.
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
