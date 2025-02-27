<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function minimal()
    {
        return [
            "id" => $this->id,
            "attributes" => [
                "name" => $this->name,
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
                "name" => $this->name,
                "products" => ProductResource::collection($this->products),
            ]
        ];
    }
}
