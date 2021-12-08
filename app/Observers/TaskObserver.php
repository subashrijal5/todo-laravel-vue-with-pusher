<?php

namespace App\Observers;

use App\Classes\NotifyStaff;
use App\Events\TaskCreated;
use  App\Models\Task;

class TaskObserver
{
    public function __construct()
    {
    }

    public function deleting(Task $task)
    {
    }

    public function creating(Task $task)
    {
    }

    public function saved(Task $task)
    {
    }

    public function created(Task $task)
    {
    }

    public function updated(Task $task)
    {
    }

    public function deleted(Task $task)
    {
    }

    public function restored(Task $Task)
    {
    }
}
