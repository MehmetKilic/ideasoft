<?php

namespace App\RepositoryInterfaces;

use Illuminate\Database\Eloquent\Builder;

interface OrderRepositoryInterface
{
    /**
     * @return mixed
     */
    public function model();

    /**
     * @return mixed
     */
    public function getQueryBuilder();

    /**
     * @param Builder $builder
     * @return mixed
     */
    public function setQueryBuilder(Builder $builder);

    /**
     * @param $columns
     * @return mixed
     */
    public function all($columns = ['*']);

    /**
     * @param $id
     * @return mixed
     */
    public function findOrFail($id);

    /**
     * @param $id
     * @return mixed
     */
    public function searchOrder($id);

    /**
     * @param $data
     * @return mixed
     */
    public function create($data);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);
}
