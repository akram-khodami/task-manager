<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreProjectRequest;
use App\Http\Requests\V1\UpdateProjectRequest;
use App\Http\Resources\V1\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     */
    public function index()
    {
        $projects = Project::with('owner')->ownedByUser()->paginate();

        return response()->json(ProjectResource::collection($projects));
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        Gate::authorize('create', Project::class);

        $data = $request->validated();

        $data['owner_id'] = auth()->id();

        $project = Project::create($data);

        return response()->json(new ProjectResource($project->load('owner')), Response::HTTP_CREATED);
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project)
    {
        Gate::authorize('view', $project);

        $project->load(['owner', 'folders']);

        return response()->json(new ProjectResource($project));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        Gate::authorize('update', $project);

        $project->update($request->validated());

        return response()->json(new ProjectResource($project));
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project)
    {
        Gate::authorize('delete', $project);

        $project->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
