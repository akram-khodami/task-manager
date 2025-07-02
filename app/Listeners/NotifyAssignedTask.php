<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyAssignedTaskMail;

class NotifyAssignedTask
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
//        Mail::to($event->task->assignedUser->email)->send(new NotifyAssignedTaskMail($event->task));
        Mail::to($event->task->assignedUser->email)->queue(new NotifyAssignedTaskMail($event->task));

    }
}
