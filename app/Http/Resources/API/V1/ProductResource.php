<?php

namespace App\Http\Resources\API\V1;

use App\Models\Wishlist;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{

    public function minimal()
    {
        return [
            "id" => $this->id,
            "attributes" => [
                'name' => $this->name,
                'image' => $this->image,
                'category' => CategoryResource::make($this->category)->minimal(),
            ]
        ];
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "attributes" => [
                'name' => $this->name,
                'image' => $this->image,
                'category' => CategoryResource::make($this->category)->minimal(),
                "price" => $this->price,
                "stock" => $this->stock,
                "description" => $this->description,
                "inWishList" => Wishlist::where("product_id", "=", $this->id)->where("user_id", "=", auth()->user()->id)->get()->isNotEmpty() ? true : false,
                "reviews" => Product_ReviewsResource::collection($this->reviews),
            ]
        ];
    }
}
