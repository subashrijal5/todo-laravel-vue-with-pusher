<?php

namespace App\Http\Controllers;

use App\Events\TaskCreated;
use App\Events\TaskUpdate;
use App\Http\Requests\CreateTaskRequest;
use App\Repositories\TaskRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TaskController extends AppBaseController
{
    public $repository;
    public function __construct(TaskRepository $taskRepository)
    {
        $this->middleware('verified');
        $this->repository = $taskRepository;
    }
    public function FetchData()
    {
        try {
            $tasks = $this->repository->all([], null, null, ['*'], ['user', 'comments.commenter']);
            return $this->sendResponse($tasks, '');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
    //
    public function index()
    {
        return view('tasks.index');
    }

    public function store(Request $request)
    {
        try {
            $input = $request->except('user');
            $input['user_id'] = auth()->id();
            $task = $this->repository->create($input);
            broadcast(new TaskCreated(Auth::user(), $task))->toOthers();
            return $this->sendResponse($task, 'task created sucesssfully');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $task = $this->repository->find($id, ['*']);
            \throw_if(empty($task), BadRequestException::class, 'Task not found');
            $this->repository->delete($id);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
    public function update(Request $request)
    {
        try {
            $task = $this->repository->find($request->get('id', ['*']));
            if (empty($task)) {
                return $this->sendError('Task not found');
            }
            $completed = $request->get('completed', 0);
            $task =    $this->repository->update(['completed' => $completed], $task->id);
            broadcast(new TaskUpdate(Auth::user(), $task))->toOthers();
            return $this->sendResponse($task, 'Status Updated');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
}
