<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreFolderRequest;
use App\Http\Requests\V1\UpdateFolderRequest;
use App\Http\Resources\V1\FolderResource;
use App\Models\Folder;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class FolderController extends Controller
{
    /**
     * Display a listing of the folders.
     */
    public function index(): JsonResource
    {
        Gate::authorize('viewAny', Folder::class);

        $folders = Folder::with(['project', 'parent', 'children'])
            ->when(!auth()->user()->isAdmin(), function ($query) {
                $query->ownedByUser();
            })
            ->when(request('project_id'), function ($query, $projectId) {
                $query->where('project_id', $projectId);
            })
            ->when(request('parent_id'), function ($query, $parentId) {
                $query->where('parent_id', $parentId);
            })
            ->paginate();

        return FolderResource::collection($folders);
    }

    /**
     * Store a newly created folder in storage.
     */
    public function store(StoreFolderRequest $request): JsonResponse
    {
        Gate::authorize('create', Project::class);

        $folder = Folder::create($request->validated());

        return response()->json(
            [
                'message' => 'Folder is created successfully.',
                'data' => new FolderResource($folder)
            ], Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified folder.
     */
    public function show(Folder $folder): JsonResource
    {
        Gate::authorize('view', $folder);

        $folder->load(['project', 'parent', 'children']);

        return new FolderResource($folder);
    }

    /**
     * Update the specified folder in storage.
     */
    public function update(UpdateFolderRequest $request, Folder $folder): JsonResponse
    {
        //user can not changer project_id in update
        Gate::authorize('update', $folder);

        $folder->update($request->validated());

        return response()->json(
            [
                'success' => true,
                'message' => 'Folder updated successfully.',
                'data' => new FolderResource($folder),
            ]
        );
    }

    /**
     * Remove the specified folder from storage.
     */
    public function destroy(Folder $folder): JsonResponse
    {
        Gate::authorize('delete', $folder);

        if ($folder->children()->exists() || $folder->tasks()->exists()) {

            return response()->json(
                [
                    'success' => false,
                    'message' => 'This folder has children or task, so You can`t remove it.'
                ], Response::HTTP_CONFLICT);

        }

        $folder->delete();

        return response()->json(
            [
                'success' => true,
                'message' => 'Folder removed successfully.',
            ], 200
        );
    }
}
