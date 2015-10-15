<?php

namespace Modules\Support\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class Repository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;


    /**
     * @throws \Bosnadev\Repositories\Exceptions\RepositoryException
     */
    public function __construct()
    {
        $this->model = $this->makeModel();
    }


    /**
     * Specify Model class name
     *
     * @return string
     */
    public abstract function model();


    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }


    /**
     * @param array $columns
     *
     * @return Collection
     */
    public function all($columns = ['*'])
    {
        return $this->getModel()->get($columns);
    }


    /**
     * @param  string $value
     * @param  string $key
     *
     * @return array
     */
    public function lists($value, $key = null)
    {
        $lists = $this->getModel()->lists($value, $key);

        if (is_array($lists)) {
            return $lists;
        }

        return $lists->all();
    }


    /**
     * @param int   $perPage
     * @param array $columns
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = 15, $columns = ['*'])
    {
        return $this->getModel()->paginate($perPage, $columns);
    }


    /**
     * @param array $data
     *
     * @return Model
     */
    public function create(array $data)
    {
        return $this->getModel()->create($data);
    }


    /**
     * @param array  $data
     * @param        $id
     * @param string $attribute
     *
     * @return Model
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $model = $this->getModel()->where($attribute, '=', $id)->first();

        if(is_null($model)) {
            throw new ModelNotFoundException;
        }

        $model->update($data);

        return $model;
    }


    /**
     * @param $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        return $this->getModel()->destroy($id);
    }


    /**
     * @param       $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        return $this->getModel()->find($id, $columns);
    }


    /**
     * @param int|string $id
     * @param array      $columns
     *
     * @return Model
     * @throws ModelNotFoundException
     */
    public function findOrFail($id, $columns = ['*'])
    {
        return $this->getModel()->findOrFail($id, $columns);
    }


    /**
     * @param string $attribute
     * @param string $value
     * @param array  $columns
     *
     * @return Model
     */
    public function findBy($attribute, $value, $columns = ['*'])
    {
        return $this->getModel()->where($attribute, '=', $value)->first($columns);
    }


    /**
     * @param string $attribute
     * @param string $value
     * @param array  $columns
     *
     * @return Collection
     */
    public function findAllBy($attribute, $value, $columns = ['*'])
    {
        return $this->getModel()->where($attribute, '=', $value)->get($columns);
    }


    /**
     * Find a collection of models by the given query conditions.
     *
     * @param array $where
     * @param array $columns
     * @param bool  $or
     *
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public function findWhere($where, $columns = ['*'], $or = false)
    {
        $model = $this->getModel();

        foreach ($where as $field => $value) {
            if ($value instanceof \Closure) {
                $model = ( ! $or ) ? $model->where($value) : $model->orWhere($value);
            } elseif (is_array($value)) {
                if (count($value) === 3) {
                    list( $field, $operator, $search ) = $value;
                    $model = ( ! $or ) ? $model->where($field, $operator, $search) : $model->orWhere($field, $operator,
                        $search);
                } elseif (count($value) === 2) {
                    list( $field, $search ) = $value;
                    $model = ( ! $or ) ? $model->where($field, '=', $search) : $model->orWhere($field, '=', $search);
                }
            } else {
                $model = ( ! $or ) ? $model->where($field, '=', $value) : $model->orWhere($field, '=', $value);
            }
        }

        return $model->get($columns);
    }


    /**
     * @return Model
     * @throws RepositoryException
     */
    public function makeModel()
    {
        $model = app()->make($this->model());

        if ( ! $model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $model;
    }
}