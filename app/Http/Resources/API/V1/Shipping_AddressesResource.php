<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class Shipping_AddressesResource extends JsonResource
{
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
                "firstName"    => $this->first_name,
                "lastName"     => $this->last_name,
                "addressLine1" => $this->address_line_1,
                "addressLine2" => $this->address_line_2,
                "city"         => $this->city,
                "state"        => $this->state,
                "zipCode"      => $this->zip_code,
                "country"      => $this->country,
            ]
        ];
    }
}
