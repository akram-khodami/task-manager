<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Enums\TaskPriority;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'priority',
        'folder_id',
        'created_by',
        'assigned_to'
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id', 'folder');
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class);
    }

    //use Accessor for access to $task->status_title
    public function getStatusTitleAttribute()
    {
        return TaskStatus::from($this->status)->label();
    }

    public function getPriorityTitleAttribute()
    {
        return TaskPriority::from($this->priority)->label();
    }
}
