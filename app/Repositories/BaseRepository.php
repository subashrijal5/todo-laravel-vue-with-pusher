<?php

namespace App\Repositories;

use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $app
     *
     * @throws \Exception
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     * Get searchable fields array
     *
     * @return array
     */
    abstract public function getFieldsSearchable();

    /**
     * Configure the Model
     *
     * @return string
     */
    abstract public function model();

    /**
     * Make Model instance
     *
     * @throws \Exception
     *
     * @return Model
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new \Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }


    /**
     * Retrieve all records with given filter criteria
     *
     * @param array $search
     * @param int|null $skip
     * @param int|null $limit
     * @param array $columns
     * @param array $eagerWith
     * @param array $orders = ["field" => "asc", "field" => "desc"]
     * @param array $exclude = ["value1", "value2"]
     * @param string $group
     * @param array $withCount = ["relation1", "relation2", "relation3"]
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all($search = [], $skip = null, $limit = null, $columns = ['*'], $eagerWith = [], $orders = [], $exclude = [], $group = null, $withCount = [], $whereHas = [], $scopeWith = null)
    {
        $query = $this->allQuery($search, $skip, $limit, $exclude, $orders);

        if (!empty($withCount)) {
            foreach ($withCount as $with) {
                $query->withCount($with);
            }
        }

        if (!empty($eagerWith)) {
            $query->with($eagerWith);
        }

        if (!empty($scopeWith)) {
            $query->$scopeWith();
        }

        if (!empty($whereHas)) {
            foreach ($whereHas as $with) {
                $query->whereHas($with);
            }
        }

        if (!empty($group)) {
            $query->groupBy(DB::raw($group));
        }

        return empty($columns) ? $query->get() : $query->select($columns)->get();
    }

    /**
     * Create model record
     *
     * @param array $input
     *
     * @return Model
     */
    public function create($input)
    {
        $model = $this->model->newInstance($input);

        $model->save();

        return $model;
    }

    /**
     * Find model record for given id
     *
     * @param int $id
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function find($id, $columns = ['*'], $withTrashed = false, $slug = false)
    {
        $query = $this->model->newQuery();

        $columns = array_map(function ($column) {
            return "{$this->model->getTable()}.$column";
        }, $columns);

        if ($withTrashed) {
            $query->withTrashed();
        }

        $field = 'id';
        if ($slug == true) {
            $field = 'slug';
        }

        return $query->where("{$this->model->getTable()}.$field", $id)->select($columns)->first();
    }

    /**
     * Update model record for given id
     *
     * @param array $input
     * @param int $id
     * @param boolean $withTrashed
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model
     */
    public function update(array $input, $id, $withTrashed = false)
    {
        $query = $this->model->newQuery();

        if ($withTrashed) {
            $query->withTrashed();
        }

        $model = $query->findOrFail($id);

        $model->fill($input);

        $model->save();

        return $model;
    }

    /**
     * @param int $id
     *
     * @throws \Exception
     *
     * @return bool|mixed|null
     */
    public function delete($id)
    {
        $query = $this->model->newQuery();

        $model = $query->withTrashed()->findOrFail($id);

        return $model->delete();
    }

    /**
     * @param int $id
     *
     * @throws \Exception
     *
     * @return bool|mixed|null
     */
    public function restore($id)
    {
        $query = $this->model->newQuery();

        $model = $query->withTrashed()->findOrFail($id);

        $model->is_deleted = 0;

        return $model->restore();
    }

    public function updateOrCreate($data)
    {
        $id = $data['id'];
        unset($data['id']);
        $search = ['id' => $id];
        return $this->model->updateOrCreate($search, $data);
    }

    public function deleteByType($ids, $source_id, $type = 'user_id')
    {
        $query = $this->model->where($type, $source_id);
        if (!empty($ids)) {
            $query->whereNotIn('id', $ids);
        }
        $query->delete();
    }

    public function forcedelete($id)
    {
        $query = $this->model->newQuery();

        $model = $query->withTrashed()->findOrFail($id);

        return $model->forceDelete();
    }

    /**
     * Build a query for retrieving all records.
     *
     * @param array $search
     * @param int|null $skip
     * @param int|null $limit
     * @param array $exclude = []
     * @param array $orders = ["field" => "asc", "field" => "desc"]
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function allQuery($search = [], $skip = null, $limit = null, $exclude = [], $orders = [])
    {
        $query = $this->model->newQuery();
        $table = $this->model->getTable();
        if (count($search)) {
            $casts = $this->model->getCasts();
            foreach ($search as $key => $value) {
                $field = str_replace("$table.", '', $key);
                if (in_array($field, $this->getFieldsSearchable())) {
                    $type = isset($casts[$field]) ? $casts[$field] : '';
                    if (strpos($type, 'string') !== false) {
                        if (isset($value['exact'])) {
                            $query->where($key, $value['exact']);
                        } else {
                            $query->whereRaw("$key LIKE '$value%'");
                        }
                    } elseif (is_array($value)) {
                        if (isset($value['conditions']) && !empty($value['conditions'])) {
                            $query->where(function ($query) use ($key, $value) {
                                foreach ($value['conditions'] as $conditions) {
                                    foreach ($conditions as $operator => $string) {
                                        if ($operator == 'OR') {
                                            foreach ($string as $orOperator => $orString) {
                                                if ($orOperator == 'isNull') {
                                                    ($orString) ? $query->orWhereNull($key) : $query->orWhereNotNull($key);
                                                } else {
                                                    $query->orWhere($key, $orOperator, $orString);
                                                }
                                            }
                                        } elseif ($operator == 'isNull') {
                                            ($string) ? $query->orWhereNull($key) : $query->orWhereNotNull($key);
                                        } else {
                                            $query->where($key, $operator, $string);
                                        }
                                    }
                                }
                            });
                        } else {
                            $query->whereIn($key, $value);
                        }
                    } else {
                        $query->where($key, $value);
                    }
                } elseif (\Illuminate\Support\Str::lower($key) == 'raw_query') {
                    $query->whereRaw($value);
                }
            }
        }

        if (!is_null($skip)) {
            $query->skip($skip);
        }

        if (!is_null($limit)) {
            $query->limit($limit);
        }

        if (!empty($exclude)) {
            foreach ($exclude as $key => $value) {
                $type = isset($casts[$key]) ? $casts[$key] : '';
                if (strpos($type, 'string') !== false) {
                    $query->whereRaw("$key NOT LIKE '%$value%'");
                } elseif (is_array($value)) {
                    $query->whereNotIn($key, $value);
                } else {
                    $query->where($key, '!=', $value);
                }
            }
        }

        if (!empty($orders)) {
            foreach ($orders as $field => $order) {
                $query->orderBy($field, $order);
            }
        }

        return $query;
    }
}
