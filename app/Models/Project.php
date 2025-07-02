<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ProjectStatus;
use App\Enums\ProjectPriority;

class Project extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',//enum?
        'start_date',//jalali?
        'end_date',//jalali?
        'priority',//enum?
        'owner_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    public function tasks()
    {
        return $this->hasManyThrough(Task::class, Folder::class);
    }

    /**
     * Scope a query to only include owned users.
     */
    protected function scopeOwnedByUser(Builder $query): void
    {
        $query->where('owner_id', auth()->id());
    }

    //use Accessor for access to $folder->status_title
    public function getStatusTitleAttribute()
    {
        return ProjectStatus::from($this->status)->label();
    }

    public function getPriorityTitleAttribute()
    {
        return ProjectPriority::from($this->priority)->label();
    }


}
