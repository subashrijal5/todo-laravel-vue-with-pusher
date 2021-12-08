<?php

namespace App\Http\Controllers;

use App\Helpers\FunctionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 *ass should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{
    public $entity;
    public $repository;

    public function getEntity($entity = null, $disk = null)
    {
        $this->entity = FunctionHelper::getEntity($entity);
        $this->entity['disk'] = $disk;
    }


    public function sendResponse($result, $message)
    {
        $response = [
            "success" => true,
            "message" => $message,
            "data" => $result
        ];

        return Response::json($response);
    }

    public function sendError($error, $code = 404)
    {
        $response = [
            'success' => false,
            'error' => $error
        ];
        return Response::json($response, $code);
    }

    public function sendSuccess($message)
    {
        return Response::json([
            'success' => true,
            'message' => $message
        ], 200);
    }

    /**
     * Archive the specified Model from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroyAjax($id)
    {
        $record = $this->repository->find($id);

        if (empty($record)) {
            return $this->sendError($this->entity['view'] . ' not found');
        }

        $this->repository->delete($id);

        return $this->sendResponse($record, $this->entity['view'] . ' archived successfully.');
    }

    /**
     * Delete the specified Model from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function deletedAjax($id)
    {
        $record = $this->repository->find($id, ['*'], true);

        if (empty($record)) {
            return $this->sendError($this->entity['view'] . ' not found');
        }

        $record->is_deleted = 1;
        $record->save();

        $this->repository->delete($id);

        return $this->sendResponse($record, $this->entity['view'] . ' deleted successfully.');
    }

    /**
     * updateDestroy the specified Model in storage.
     *
     * @param  int              $id
     * @param Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function updateDestroy($id, Request $request)
    {
        $record = $this->repository->find($id, ['*'], true);
        $message = '';
        // dd($this->entity);
        if (empty($record)) {
            return $this->sendError($this->entity['view'] . ' not found');
        }

        try {
            switch ($request->method()) {
                case 'PATCH':
                    if ($request->get('process', null) == 'restore') {
                        if ($this->entity['view'] == 'employer_jobs') {
                            $message = 'Activate';
                        } else {
                            $message = 'Activate';
                        }
                        $record = $this->repository->restore($id);
                    } else {
                        $input = $request->only('status');
                        $message = 'status updated';
                        $record = $this->repository->update($input, $id);
                    }
                    break;
                case 'DELETE':
                    if ($request->get('process', null) == 'archive') {
                        if ($this->entity['view'] == 'employer_jobs') {
                            $message = 'Deactivate';
                        } else {
                            $message = 'Deactivate';
                        }
                    } else {
                        $message = 'Deleted';
                        $record->is_deleted = 1;
                        $record->save();
                    }
                    $this->repository->delete($id);
                    break;
                default:
                    return $this->sendError('Invalid method used, only Patch or Delete allowed.', 400);
                    break;
            }
            return $this->sendResponse($record, $this->entity['singular'] . " $message successfully.");
        } catch (Throwable $e) {
            return $this->sendError($e->getMessage(), $e->getCode() == 0 ? 400 : $e->getCode());
        }
    }
}
