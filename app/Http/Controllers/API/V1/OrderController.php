<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display all wishlist products of the current user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = $request->user()->orders;
        if(!$orders)
            return response()->json(['message' => 'you did no make any orders yet'], 404);

        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $total = 0;
        $new_order = Order::create();

        foreach ($request->products as $product) {
            $request->merge([
                'order_id' => $new_order->id,
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
            ]);
            (new Order_DetailsController)->store($request);

            $total += Product::find($product['id'])->price;
        }

        $new_order->update([
            'user_id' => $request->user()->id,
            'total' => $total,
        ]);

        return OrderResource::make($new_order);
    }

    /**
     * Remove an order of the current user
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $order = $request->user()->orders()->find($id);
        if (!$order)
            return response()->json(['message' => 'you do not have an order with id ' . $id], 404);

        $delete_result = $order->delete();
        return $delete_result ? OrderResource::make($order) : response()->json(['message' => 'an error occured'], 500);
    }
}
