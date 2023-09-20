<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Order_DetailsResource;
use App\Models\Order;
use App\Models\Order_Details;
use App\Models\Product;
use Illuminate\Http\Request;

class Order_DetailsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order = $request->user()->orders->find($request->order_id);
        if(!$order)
            return response()->json(['message' => 'you do not have an order with id ' . $request->order_id], 404);

        $product = Product::find($request->product_id);
        if(!$product)
            return response()->json(['message' => 'no product with id ' . $request->product_id], 404);

        $request->validate([
            'order_id'   => 'reqiured|exists:orders,id',
            'product_id' => 'reqiured|exists:products,id',
            'quantity'   => 'reqiured|integer|min:1|max:' . $product->stock,
            'price'      => 'reqiured|decimal',
        ]);

        $new_order_detail = Order_Details::create([
            'order_id' => $request->order_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $product->price,
        ]);

        $product->update([
            'stock' => $product->stock - $request->quantity,
        ]);

        return Order_DetailsResource::make($new_order_detail);
    }
}
