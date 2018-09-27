<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TrackingOrder;

class TrackingOrdersController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param TrackingOrder $trackingOrder trackingOrder object
     *
     * @return \Illuminate\Http\Response
    */
    public function destroy(TrackingOrder $trackingOrder)
    {
        $trackingOrder->delete();
        flash(trans('user.admin.message.success'))->success();
        return redirect()->route('order.show', ['order' => $trackingOrder->order_id]);
    }
}
