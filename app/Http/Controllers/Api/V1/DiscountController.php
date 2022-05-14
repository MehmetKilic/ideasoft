<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use App\Models\OrderItem;

class DiscountController extends Controller
{
	public $repository;

	public function __construct(OrderRepository $orderRepository)
    {
        $this->repository = $orderRepository;
        $this->discounts  = [];
    }

    public function getOrder($id)
    {
    	return $this->repository->findOrFail($id);
    }

	public function discountThousand($id)
	{
		$priceLimit = 1000;

		$getOrder = $this->getOrder($id);
		if($getOrder->total >= $priceLimit){
			$this->discounts[] = [
				"discountReason" => "10_PERCENT_OVER_1000",
				"discountAmount" => $getOrder->total / 10,
				"subTotal" => isset($this->discount['subTotal']) ? $this->discount['subTotal'] - $getOrder->total / 10 : $getOrder->total - $getOrder->total / 10
			];
		}
	}

	public function discountBuySix($id)
	{
		$buyLimit = 6;
		$categoryId = 2;

		$getOrderItems = OrderItem::where('order_id', $id)->get();
		$getOrder = $this->getOrder($id);
		foreach($getOrderItems as $item){
			if($item->product->category_id = $categoryId && $item->quantity == $buyLimit){
				$this->discounts[] = [
					"discountReason" => "BUY_6_GET_1",
					"discountAmount" => $item->unit_price,
					"subTotal" => isset($this->discount['subTotal']) ? $this->discount['subTotal'] - $item->unit_price : $getOrder->total - $item->unit_price
				];
			}
		}
	}

	public function index($id)
	{
		$this->discountBuySix($id);
		$this->discountThousand($id);

		$totalDiscount = array_sum(array_column($this->discounts,'discountAmount'));
		$result = [
			"orderId" => $id,
			"discounts" => $this->discounts,
			"totalDiscount" => $totalDiscount,
			"discountedTotal" => $this->getOrder($id)->total - $totalDiscount
		];

		return response()->json($result, 200);
	}
}