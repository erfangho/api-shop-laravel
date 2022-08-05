<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class OrderController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return JsonResponse
     */
    public function show(Order $order)
    {
        $current_user = Auth::user();

        $order = Order::where('user_id', $current_user->id)
            ->where('status', 'pending')->first();

        $orderProducts = OrderProduct::where('order_id', $order->id)->get();

        $totalPrice = 0;
        foreach($orderProducts as $orderProduct) {
            $totalPrice += ($orderProduct->product->price * ((100 - $orderProduct->product->discount)/100)) * $orderProduct->quantity;
        }

        $message = array(
            'name' => $current_user->name,
            'email' => $current_user->email,
            'phone' => $current_user->phone,
            'address to deliver' => $current_user->address,
            'products' => $orderProducts,
            'total price' => $totalPrice,
            'status' => $order->status,
        );

        return response()->json($message, ResponseAlias::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return JsonResponse
     */
    public function cancel(Order $order)
    {
        $current_user = Auth::user();

        $order = Order::where('user_id', $current_user->id)->first();

        $order->status = 'cancelled';

        $order->save();

        $orderProducts = OrderProduct::where('order_id', $order->id)->get();

        foreach($orderProducts as $orderProduct) {
            $orderProduct->delete();
        }

        $carts = Cart::where('user_id', $current_user->id)->first();

        foreach ($carts as $cart) {
            $cart->delete();
        }

        return response()->json(['message' => 'Order cancelled'], ResponseAlias::HTTP_OK);
    }

    public function payOrder()
    {
        $current_user = Auth::user();

        $order = Order::where('user_id', $current_user->id)->where('status', 'pending')->first();

        $orderProducts = OrderProduct::where('order_id', $order->id)->get();

       //TODO : implement payment. :)

        $order->status = 'paid';

        $order->save();

        $orderProducts = OrderProduct::where('order_id', $order->id)->get();

        foreach($orderProducts as $orderProduct) {
            $orderProduct->delete();
        }

        $carts = Cart::where('user_id', $current_user->id)->get();

        foreach ($carts as $cart) {
            $cart->delete();
        }

        return response()->json(['message' => 'Order paid'], ResponseAlias::HTTP_OK);
    }

    public function orderHistory()
    {
        $current_user = Auth::user();

        $orders = Order::where('user_id', $current_user->id)->whereNot('status', 'pending')->get();

        return response()->json($orders, ResponseAlias::HTTP_OK);
    }
}
