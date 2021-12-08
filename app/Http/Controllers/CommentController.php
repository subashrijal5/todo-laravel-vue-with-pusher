<?php

namespace App\Http\Controllers;

use App\Events\Commented;
use App\Events\TaskUpdate;
use App\Repositories\CommentRepository;
use App\Repositories\TaskRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends AppBaseController
{
    public function __construct(CommentRepository $repo, TaskRepository $taskRepository)
    {
        $this->repo = $repo;
        $this->taskRepository = $taskRepository;
    }
    public function store(Request $request)
    {
        try {
            return $request->all();
            $taskId = $request->get('taskId', \null);
            $task = $this->taskRepository->find($taskId, ['*']);
            if (!empty($task)) {
                $createData = ['comment' => $request->get('comment', ''), 'task_id' => $task->id ?? \null, 'commenter_id' => Auth::id()];
                $data = $this->repo->create($createData);
                broadcast(new TaskUpdate(Auth::user(), $task))->toOthers();
            }
            return $this->sendResponse($data, 'Commented');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
}
