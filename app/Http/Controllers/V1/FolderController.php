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

/**
 * @OA\Tag(
 *     name="folders",
 *     description="Folder management endpoints"
 * )
 */
class FolderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/folders",
     *     tags={"folders"},
     *     summary="List folders",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="project_id",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *         description="Filter by project ID"
     *     ),
     *     @OA\Parameter(
     *         name="parent_id",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *         description="Filter by parent folder ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of folders",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Folder")
     *         )
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/folders",
     *     tags={"folders"},
     *     summary="Create a folder",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","project_id"},
     *             @OA\Property(property="name", type="string", example="Sprint 1"),
     *             @OA\Property(property="project_id", type="integer", example=1),
     *             @OA\Property(property="parent_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Folder created",
     *         @OA\JsonContent(ref="#/components/schemas/Folder")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/folders/{id}",
     *     tags={"folders"},
     *     summary="Show a folder",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Folder details",
     *         @OA\JsonContent(ref="#/components/schemas/Folder")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Folder not found"
     *     )
     * )
     */
    public function show(Folder $folder): JsonResource
    {
        Gate::authorize('view', $folder);

        $folder->load(['project', 'parent', 'children']);

        return new FolderResource($folder);
    }

    /**
     * @OA\Put(
     *     path="/api/folders/{id}",
     *     tags={"folders"},
     *     summary="Update a folder",
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
     *             @OA\Property(property="name", type="string", example="Sprint 2"),
     *             @OA\Property(property="parent_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Folder updated",
     *         @OA\JsonContent(ref="#/components/schemas/Folder")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Folder not found"
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/folders/{id}",
     *     tags={"folders"},
     *     summary="Delete a folder",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Folder deleted"
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Folder has children or tasks, cannot be deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Folder not found"
     *     )
     * )
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
