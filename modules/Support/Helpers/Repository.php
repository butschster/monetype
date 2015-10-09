<?php

namespace Modules\Support\Helpers;

use App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Bosnadev\Repositories\Contracts\RepositoryInterface;

abstract class Repository implements RepositoryInterface
{

    /**
     * @var App
     */
    private $app;

    /**
     * @var Model
     */
    protected $model;


    /**
     * @param App $app
     *
     * @throws \Bosnadev\Repositories\Exceptions\RepositoryException
     */
    public function __construct(App $app)
    {
        $this->app   = $app;
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
        return $this->getModel();
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
     * @return mixed
     */
    public function paginate($perPage = 1, $columns = ['*'])
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
        return $this->getModel()->where($attribute, '=', $id)->update($data);
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
     * @return Model
     * @throws RepositoryException
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if ( ! $model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $model;
    }
}