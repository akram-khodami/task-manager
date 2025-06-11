<?php

namespace App\Policies;

use App\Models\Folder;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FolderPolicy
{

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Folder $folder): bool
    {
        return $user->id === $folder->project->owner_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Project $project): bool
    {
        return $user->id === $project->owner_id;//???
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Folder $folder): bool
    {
        return $user->id === $folder->project->owner_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Folder $folder): bool
    {
        return $user->id === $folder->project->owner_id;
    }
}
