<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Shipping_AddressesResource;
use App\Models\Shipping_Addresses;
use Illuminate\Http\Request;

class Shipping_AddressesController extends Controller
{
    /**
     * Store a newly created shipping address
     * and assign it to the current user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name'     => 'required|string',
            'last_name'      => 'required|string',
            'address_line_1' => 'required|string',
            'address_line_2' => 'nullable|string',
            'country'        => 'required|string',
            'state'          => 'required|string',
            'city'           => 'required|string',
            'zip_code'       => 'required|numeric',
        ]);

        $new_shipping_address = Shipping_Addresses::create([
            'user_id' => $request->user()->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'zip_code' => $request->zip_code,
        ]);

        return Shipping_AddressesResource::make($new_shipping_address);
    }

    /**
     * Update the specified shipping address.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name'     => 'nullable|string',
            'last_name'      => 'nullable|string',
            'address_line_1' => 'nullable|string',
            'address_line_2' => 'nullable|string',
            'country'        => 'nullable|string',
            'state'          => 'nullable|string',
            'city'           => 'nullable|string',
            'zip_code'       => 'nullable|numeric',
        ]);

        $shipping_address = $request->user()->shippingAddresses()->find($id);
        if (!$shipping_address)
            return response()->json(['message' => 'you do not have an address with id ' . $id], 404);

        $update_result = $shipping_address->update([
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'country'        => $request->country,
            'state'          => $request->state,
            'city'           => $request->city,
            'zip_code'       => $request->zip_code,
        ]);

        return $update_result ? Shipping_AddressesResource::make($shipping_address) : response()->json(['message' => 'an error occured'], 500);
    }

    /**
     * Remove shipping address from current user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $shipping_address = $request->user()->shippingAddresses()->find($id);
        if (!$shipping_address)
            return response()->json(['message' => 'you do not have an address with id ' . $id], 404);

        $delete_result = $shipping_address->delete();
        return $delete_result ? Shipping_Addresses::make($shipping_address) : response()->json(['message' => 'an error occured'], 500);
    }
}
