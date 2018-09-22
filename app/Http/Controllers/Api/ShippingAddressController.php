<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\CreateShippingAddressRequests;
use App\ShippingAddress;
use App\Shipping;

class ShippingAddressController extends ApiController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request get request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateShippingAddressRequests $request)
    {
        try {
            $userId = Auth::id();
            $shipping = ShippingAddress::create([
                'user_id' => $userId,
                'address' => $request->address,
                'is_default' => ShippingAddress::ADDRESS,
            ]);
            return $this->successResponse($shipping->load('user'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse(trans('errors.create_fail'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
    
    /**
     * Delete a shipping address
     *
     * @param \App\ShippingAddress $shipping ShippingAddress
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShippingAddress $shipping)
    {
        try {
            $userId = Auth::id();
            if ($userId == $shipping->user_id) {
                $shipping->delete();
                return $this->responseDeleteSuccess(Response::HTTP_OK);
            } else {
                return $this->errorResponse(trans('errors.delete_fail'), Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        } catch (Exception $e) {
            return $this->errorResponse(trans('errors.delete_fail'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\ShippingAddress                                $shipping ShippingAddress
     * @param App\Http\Requests\Api\CreateShippingAddressRequests $request  request
     *
     * @return \Illuminate\Http\Response
    */
    public function update(ShippingAddress $shipping, CreateShippingAddressRequests $request)
    {
        try {
            $user = Auth::user();
            if ($user->id == $shipping->user_id) {
                $shipping->update([
                    'address'=> $request->address,
                ]);
                return $this->successResponse($shipping->load('user'), Response::HTTP_OK);
            } else {
                return $this->errorResponse(trans('errors.update_fail'), Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        } catch (Exception $e) {
            return $this->errorResponse(trans('errors.delete_fail'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
