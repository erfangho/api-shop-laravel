<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $current_user = Auth::user();

        $carts = Cart::where('user_id', $current_user->id)->get();

        return response()->json($carts, ResponseAlias::HTTP_OK);
    }

    public function addToCart(Request $request, $id)
    {
        try {
            $current_user = Auth::user();

            $product = Product::find($id);

            if($product->quantity > 0 && $product->quantity >= $request->quantity) {
                $cart = new Cart();
                if($cart->query()->where('product_id', $id)->where('user_id', $current_user->id)->exists()) {
                    $cart = $cart->query()->where('product_id', $id)->where('user_id', $current_user->id)->first();
                    $cart->quantity += $request->quantity;
                    $cart->update();
                } else {
                    $cart->user_id = $current_user->id;
                    $cart->product_id = $id;
                    $cart->quantity = $request->quantity;
                    $cart->save();
                }
            } else {
                return response()->json(['message' => 'Product quantity is not enough'], ResponseAlias::HTTP_NOT_FOUND);
            }

            $product->quantity = $product->quantity - $request->quantity;
            $product->update();

            return response()->json(['message' => 'Product added to cart successfully'], ResponseAlias::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function removeFromCart($id)
    {
        try {
            $current_user = Auth::user();

            $cart = Cart::query()->where('user_id', $current_user->id)->where('product_id', $id)->first();
            $product = Product::find($id);
            $product->quantity = $product->quantity + $cart->quantity;
            $product->update();
            $cart->delete();

            return response()->json(['message' => 'Product removed from cart successfully'], ResponseAlias::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function submitCart()
    {
        try {
            $current_user = Auth::user();

            $carts = Cart::query()->where('user_id', $current_user->id)->get();

            $order = new Order();

            if($order->query()->where('user_id', $current_user->id)->where('status', 'pending')->exists()) {
                return response()->json(['message' => 'You have an active order'], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
            } else {
                $order->user_id = $current_user->id;
                $order->status = 'pending';
                $order->payment_status = 'pending';
                $order->payment_method = 'online';
                $order->save();

                foreach($carts as $cart) {
                    $orderProduct = new OrderProduct();
                    $orderProduct->order_id = $order->id;
                    $orderProduct->product_id = $cart->product_id;
                    $orderProduct->quantity = $cart->quantity;
                    $orderProduct->save();
                }
            }

            return response()->json(['message' => 'Cart submitted successfully'], ResponseAlias::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }
}

