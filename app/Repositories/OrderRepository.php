<?php

namespace App\Repositories;

use App\Models\Order;
use App\RepositoryInterfaces\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class OrderRepository implements OrderRepositoryInterface
{
    protected $builder;

    protected $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function model()
    {
        return $this->model;
    }

    public function getQueryBuilder()
    {
        return $this->builder = $this->model()::query();
    }

    public function setQueryBuilder(Builder $builder)
    {
        $this->builder = $builder;

        return $this;
    }

    public function all($columns = ['*'])
    {
        return $this->getQueryBuilder()->with([
                'customer',
                'items'
            ])->get($columns);
    }

    public function findOrFail($id)
    {
        return $this->getQueryBuilder()->findOrfail($id);
    }

    public function searchOrder($id)
    {
        return $this->getQueryBuilder()->where('id', $id)->with([
                'customer',
                'items'
            ])->get();
    }

    public function create($data)
    {
        return $this->getQueryBuilder()->create($data);
    }

    public function delete($id)
    {
        $model = $this->findOrFail($id);

        $model->delete();

        return $model;
    }
}
