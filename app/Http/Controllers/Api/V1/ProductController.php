<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public $repository;

    public function __construct(ProductRepository $productRepositoryInterface)
    {
        $this->repository = $productRepositoryInterface;
    }

    public function create(Request $request)
    {
        return $this->repository->create($request->all());
    }
}
