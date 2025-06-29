<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id', 'project_id'];

    public function parent()
    {
        return $this->belongsTo(Folder::class);
    }

    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function getProjectName()
    {
        return $this->project ? $this->project->name : 'No Project';
    }

    public function getParentName()
    {
        return $this->parent ? $this->parent->name : 'Root';
    }

    public function getChildrenNames()
    {
        return $this->children->pluck('name');
    }

    public function getTaskCount()
    {
        return $this->tasks->count();
    }

    /**
     * Scope a query to only include owned users.
     */
    #[Scope]
    protected function ownedByUser(Builder $query): void
    {
        $query->whereHas('project', function ($query) {

            $query->where('owner_id', auth()->id());

        });
    }
}
