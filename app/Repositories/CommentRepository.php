<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Models\Task;
use App\Repositories\BaseRepository;


/**
 * Class CommentRepository
 * @package App\Repositories
 * @version
 */

class CommentRepository extends BaseRepository
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
        return Comment::class;
    }
}
