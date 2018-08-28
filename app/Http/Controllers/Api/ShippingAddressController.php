<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\ShippingAddress;
use App\Shipping;

class ShippingAddressController extends ApiController
{
    /**
     * Delete a shipping address
     *
     * @param ShippingAddress $shipping ShippingAddress
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShippingAddress $shipping, Request $request)
    {
        try {
            $userId = Auth::id();
            if ($userId == $shipping->user_id) {
                $shipping->delete();
            }
            return $this->responseDeleteSuccess(Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse(trans('errors.update_fail'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
