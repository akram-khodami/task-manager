<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreFolderRequest;
use App\Http\Requests\V1\UpdateFolderRequest;
use App\Http\Resources\V1\FolderResource;
use App\Models\Folder;
use App\Models\Project;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class FolderController extends Controller
{
    /**
     * Display a listing of the folders.
     */
    public function index()
    {
        $folders = Folder::with(['project', 'parent', 'children'])
            ->ownedByUser()
            ->when(request('project_id'), function ($query, $projectId) {
                $query->where('project_id', $projectId);
            })
            ->when(request('parent_id'), function ($query, $parentId) {
                $query->where('parent_id', $parentId);
            })
            ->paginate();

        return response()->json(FolderResource::collection($folders));
    }

    /**
     * Store a newly created folder in storage.
     */
    public function store(StoreFolderRequest $request)
    {
        Gate::authorize('create', Project::class);

        $folder = Folder::create($request->validated());

        return response()->json(new FolderResource($folder), Response::HTTP_CREATED);
    }

    /**
     * Display the specified folder.
     */
    public function show(Folder $folder)
    {
        Gate::authorize('view', $folder);

        $folder->load(['project', 'parent', 'children']);

        return response()->json(new FolderResource($folder));
    }

    /**
     * Update the specified folder in storage.
     */
    public function update(UpdateFolderRequest $request, Folder $folder)
    {
        Gate::authorize('update', $folder);

        $folder->update($request->validated());

        return response()->json(new FolderResource($folder));
    }

    /**
     * Remove the specified folder from storage.
     */
    public function destroy(Folder $folder)
    {
        Gate::authorize('delete', $folder);

        if ($folder->children()->exists() || $folder->tasks()->exists()) {

            return response()->json(
                [
                    'success' => false,
                    'message' => 'این پوشه دارای زیرپوشه یا تسک است و قابل حذف نیست'
                ], Response::HTTP_CONFLICT);

        }

        $folder->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
