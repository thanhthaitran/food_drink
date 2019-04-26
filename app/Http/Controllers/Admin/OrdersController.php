<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeStatusRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Order;
use App\OrderDetail;
use App\Note;
use App\Mail\ChangeStatusOrderMail;
use Auth;
use DB;

class OrdersController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @param \Illuminate\Http\Request $request request
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $search = $request->search;
        if ($search != '') {
            $orders = Order::with('user', 'notes')->whereHas('user', function ($query) use ($search) {
                return $query->where('name', 'Like', "%$search%")
                            ->orWhere("email", 'Like', "%$search%");
            });
            $orders = $orders->sortable()->paginate(config('define.number_page_products'))->appends(['search' => $search]);
        } else {
            $orders = Order::with('user', 'notes')->sortable()->paginate(config('define.number_page_products'));
        }
        return view('admin.order.index', compact('orders'));
    }

    /**
     * Display the specified resource.
     *
     * @param Order $order order object
     *
     * @return \Illuminate\Http\Response
    */
    public function show(Order $order)
    {
        $order->load('orderDetails', 'notes.user', 'trackingOrders');
        return view('admin.order.show', compact('order'));
    }

    /**
     * Update status of order
     *
     * @param \Illuminate\Http\Request $request request
     * @param \App\Models\order        $order   order
     *
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(ChangeStatusRequest $request, Order $order)
    {
        DB::beginTransaction();
        try {
            $order->trackingOrders()->create([
                'order_id' => $order->id,
                'old_status' => $order->status,
                'new_status' => $request->status,
            ]);
            $order['status'] = $request->status;
            $order->save();
            $order->notes()->create([
                'user_id' => Auth::id(),
                'content' => $request->content,
            ]);
            $data = ['name' => $order->user->name,
                'status' => $request->status,
                'pending' => $order::PENDING,
                'accepted' => $order::ACCEPTED,
                'rejected' => $order::REJECTED,
                'received' => $order::RECEIVED,
                'note' => $order->notes()->latest()->first(),
            ];
            \Mail::to($order->user->email)->send(new ChangeStatusOrderMail($data));
            DB::commit();
            return response()->json($order->load('notes', 'trackingOrders'));
        } catch (Exception $e) {
            DB::rollBack();
            return response(trans('message.post.fail_delete'), Response::HTTP_BAD_REQUEST);
        }
    }
}
