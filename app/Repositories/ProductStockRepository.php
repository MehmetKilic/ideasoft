<?php

namespace App\Repositories;

use App\Models\Product;
use App\RepositoryInterfaces\ProductStockRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use App\Models\ProductStock;

class ProductStockRepository implements ProductStockRepositoryInterface
{
    protected $builder;

    protected $model;

    public function __construct(ProductStock $model)
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

    public function find($id)
    {
        return $this->getQueryBuilder()->where('product_id', $id)->first();
    }

    public function create($data)
    {
        return $this->getQueryBuilder()->create($data);
    }

    public function update($data, $id)
    {
        $model = $this->find($id);

        $productStock = $model->quantity - $data;

        $model->update(["quantity" => $productStock]);

        return $model;
    }

}
