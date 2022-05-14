<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductStockRepository;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\OrderDeleteRequest;

class OrderController extends Controller
{
    public $repository;
    public $productRepository;
    public $productStockRepository;

    public function __construct(OrderRepository $orderRepository, ProductRepository $productRepository, ProductStockRepository $productStockRepository)
    {
        $this->repository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->productStockRepository = $productStockRepository;

    }

    public function orders()
    {
        return OrderResource::collection($this->repository->all());
    }

    public function create(OrderRequest $request)
    {
        $data = $request->validated();

        $orderItem = [];
        $orderTotal = 0;
        foreach ($data['items'] as $key => $value) {
            $getProductStock = $this->productStockRepository->find($value['product_id']);
            if($getProductStock->quantity < $value['quantity']){
                return response([
                    'message' => "İlgili ürün için yeterli stok bulunmamakta. Ürün Kodu : ".$value['product_id']
                ],400);
            }

            $getProductData = $this->productRepository->find($value['product_id']);
            $total = $value['quantity'] * $getProductData->price;
            $orderTotal += $total;
            $orderItem[] = [
                "itemData" => $value, 
                "productData" => $getProductData, 
                "total" => $total,
                "quantity" => $value['quantity']
            ];
        }

        $data["total"] = $orderTotal;

        $order = $this->repository->create($data);

        foreach($orderItem as $item){
            $order->items()->create([
                'product_id' => $item['productData']['id'],
                'quantity' => $item['itemData']['quantity'],
                'unit_price' => $item['productData']['price'],
                'total' => $item['total']
            ]);

            // Stock update
            $this->productStockRepository->update($item['quantity'], $item['productData']['id']);
        }

        return OrderResource::collection($this->repository->searchOrder($order->id));
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}
