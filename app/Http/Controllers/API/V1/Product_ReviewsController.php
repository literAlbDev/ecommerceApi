<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Product_ReviewsResource;
use App\Models\Product_Reviews;
use Illuminate\Http\Request;

class Product_ReviewsController extends Controller
{
    /**
     * add review to a product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'review'     => 'required|string',
            'rating'     => 'required|integer|min:1|max:5,',

        ]);

        $new_product_review = Product_Reviews::create([
            'product_id' => $request->product_id,
            'user_id'    => $request->user()->id,
            'review'     => $request->review,
            'rating'     => $request->rating,
        ]);

        return Product_ReviewsResource::make($new_product_review);
    }

    /**
     * Update the review of the product
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'review' => 'nullable|string',
            'rating' => 'nullable|integer|min:1|max:5,',
        ]);

        $product_review = $request->user()->reviews()->find($id);
        if (!$product_review)
            return response()->json(['message' => 'you do not have a product review with id ' . $id], 404);

        $update_result = $product_review->update([
            'review' => $request ->review,
            'rating' => $request->rating,
        ]);

        return $update_result ? Product_ReviewsResource::make($product_review) : response()->json(['message' => 'an error occured'], 500);
    }

    /**
     * Remove current users product review
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $product_review = $request->user()->reviews()->find($id);
        if (!$product_review)
            return response()->json(['message' => 'you do not have a product review with id ' . $id], 404);

        $delete_result = $product_review->delete();
        return $delete_result ? Product_ReviewsResource::make($product_review) : response()->json(['message' => 'an error occured'], 500);
    }
}
