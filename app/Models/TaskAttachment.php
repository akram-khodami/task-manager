<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAttachment extends Model
{

    use HasFactory;

    protected $fillable = ['task_id', 'file_path', 'file_name', 'file_type', 'file_size', 'uploaded_by'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class);
    }

    public function getTaskId()
    {
        return $this->task->id;
    }

    public function getTaskName()
    {
        return $this->task->title;
    }

    public function getTaskDescription()
    {
        return $this->task->description;
    }

    public function getTaskStatus()
    {
        return $this->task->status;
    }

}
