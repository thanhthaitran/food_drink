<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Order;
use App\OrderDetail;
use App\Product;
use PHPUnit\Framework\MockObject\Stub\Exception;
use App\Http\Requests\Api\SortOrderRequest;
use App\Http\Requests\Api\CreateNoteRequest;
use App\Note;
use DB;
use App\Http\Requests\Api\CreateOrderRequest;
use App\ShippingAddress;

class OrderController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SortOrderRequest $request)
    {
        $user = Auth::user();
        $orders = Order::with('notes')->where('user_id', $user->id)->sortable()->paginate($request->limit);
        $orders->appends(request()->query());
        return $this->successResponse($orders, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param Order                    $order   order object
     * @param \Illuminate\Http\Request $request request
     *
     * @return \Illuminate\Http\Response
    */
    public function show(Order $order, SortOrderRequest $request)
    {
        $user = Auth::user();
        $orderDetails = [];
        if ($order->user_id == $user->id) {
            $orderDetails = $order->orderDetails()->paginate($request->limit);
            $orderDetails->appends(request()->query());
        }
        return $this->successResponse(['order_details' => $orderDetails, 'order' => $order], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Order                          $order   order
     * @param App\Http\Requests\CreateNoteRequest $request request
     *
     * @return \Illuminate\Http\Response
    */
    public function cancel(Order $order, CreateNoteRequest $request)
    {
        DB::beginTransaction();
        $userId = Auth::id();
        if ($order->user_id == $userId) {
            try {
                $order->trackingOrders()->create([
                    'order_id' => $order->id,
                    'old_status' => $order->status,
                    'new_status' => Order::REJECTED,
                ]);
                $order->update([
                    'status' => Order::REJECTED,
                ]);
                $input['user_id'] = $userId;
                $input['order_id'] = $order->id;
                $input['content'] = $request->content;
                Note::create($input);
                $order->load('notes', 'trackingOrders');
                DB::commit();
                return $this->successResponse($order, Response::HTTP_OK);
            } catch (Exception $e) {
                DB::rollBack();
                return $this->errorResponse(trans('errors.update_fail'), Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
        return $this->errorResponse(trans('login.user.unauthorised'), Response::HTTP_UNAUTHORIZED);
    }
    
    /**
     * Api create order.
     *
     * @param \Illuminate\Http\Request $request request
     *
     * @return \Illuminate\Http\Response
    */
    public function store(CreateOrderRequest $request)
    {
        $total = 0;
        $user = Auth::user();
        if ($request->shipping_id) {
            foreach ($user->shippingAddresses as $shipping) {
                if ($shipping->id == $request->shipping_id) {
                    $request->address = $shipping->address;
                }
            }
        } elseif ($request->home) {
            $request->address = $user->userInfo->address;
        } else {
            $user->shippingAddresses()->create([
                'user_id' => $user->id,
                'address' => $request->address,
                'is_default' => ShippingAddress::ADDRESS,
            ]);
        }
        foreach ($request->product as $product) {
            $productO = Product::find($product['id']);
            $total += $product['quantity'] * $productO->price;
        }
        $order = Order::create([
            'user_id' => $user->id,
            'total' => $total,
            'status' => Order::PENDING,
            'address' => $request->address,
        ]);
        foreach ($request->product as $product) {
            $productO = Product::find($product['id']);
            $image = 'default-product.jpg';
            if ($productO->images->first()) {
                $image = $productO->images->first()->image;
            }
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'price' => $productO->price,
                'name_product' => $productO->name,
                'image' => $image,
            ]);
        }
        return $this->successResponse(['order' => $order->load('orderDetails'), 'user' => $user->load('shippingAddresses')], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Order                               $order   order
     * @param App\Http\Requests\Api\CreateOrderRequest $request request
     *
     * @return \Illuminate\Http\Response
    */
    public function update(Order $order, CreateOrderRequest $request)
    {
        if ($order->status == Order::PENDING) {
            try {
                $total = 0;
                $products = [];
                DB::beginTransaction();
                $user = Auth::user();
                if ($request->shipping_id) {
                    foreach ($user->shippingAddresses as $shipping) {
                        if ($shipping->id == $request->shipping_id) {
                            $request->address = $shipping->address;
                        }
                    }
                } elseif ($request->home) {
                    $request->address = $user->userInfo->address;
                }
                if ($request->product) {
                    foreach ($request->product as $product) {
                        $orderDetail = OrderDetail::where('order_id', $order->id)->where('product_id', $product['id'])->first();
                        if ($orderDetail) {
                            $orderDetail->update([
                                'quantity' => $product['quantity'],
                            ]);
                            $total += $product['quantity'] * $orderDetail->price;
                            array_push($products, $product);
                        }
                    }
                    OrderDetail::where('order_id', $order->id)->whereNotIn('product_id', array_pluck($request->product, 'id'))->delete();
                } else {
                    $order->update([
                        "status" => Order::REJECTED,
                    ]);
                }
                $order->update([
                    "address" => $request->address,
                    "total" => $total,
                ]);
                DB::commit();
                return $this->showOne($order->load('orderDetails'), Response::HTTP_OK);
            } catch (Exception $e) {
                DB::rollBack();
                return $this->errorResponse(trans('errors.update_fail'), Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
        return $this->errorResponse(trans('errors.update_fail'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
