<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\WishlistResource;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Display all wishlist products of the current user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return WishlistResource::collection($request->user()->wishlist);
    }

    /**
     * Add product to the wishlist of the current user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "product_id" => "exists:products,id",
        ]);

        $new_wishlist_product = Wishlist::create([
            "user_id" => $request->user()->id,
            "product_id" => $request->product_id,
        ]);

        return WishlistResource::make($new_wishlist_product);
    }

    /**
     * Remove product from current users wishlist
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $wishlist_product = $request->user()->wishlist()->find($id);
        if (!$wishlist_product)
            return response()->json(['message' => 'in your wishlist no product with id ' . $id], 404);

        $delete_result = $wishlist_product->delete();
        return $delete_result ? WishlistResource::make($wishlist_product) : response()->json(['message' => 'an error occured'], 500);
    }
}
