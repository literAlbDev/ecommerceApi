<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\ProductResource;
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
        $wishlist = $request->user()->wishlist;
        if(!$wishlist)
            return response()->json(['message' => 'no product in your wishlist'], 404);

        return ProductResource::collection($wishlist);
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
            "product_id" => "required|exists:products,id",
        ]);

        $request->user()->wishlist()->attach($request->product_id);

        return ProductResource::collection($request->user()->wishlist);
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


        $delete_result = $request->user()->wishlist()->detach($id);
        return $delete_result ? ProductResource::collection($request->user()->wishlist) : response()->json(['message' => 'an error occured'], 500);
    }
}
