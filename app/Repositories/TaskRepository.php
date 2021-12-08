<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\BaseRepository;


/**
 * Class TodoRepository
 * @package App\Repositories
 * @version
 */

class TaskRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Task::class;
    }
}
