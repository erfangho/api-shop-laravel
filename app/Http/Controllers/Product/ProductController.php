<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $products = Product::all();
        return response()->json($products, ResponseAlias::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create(ProductRequest $request)
    {
        try {
            $current_user = Auth::user();
            if($current_user['is_admin']) {
                $product = new Product();
                $product->name = $request->name;
                $product->description = $request->description;
                $product->image = $request->image;
                $product->discount = $request->discount;
                $product->quantity = $request->quantity;
                $product->category_id  = $request->category_id;
                $product->price = $request->price;
                $product->save();
                return response()->json($product, ResponseAlias::HTTP_OK);
            } else {
                return response()->json(['message' => 'You are not authorized to access this resource'], ResponseAlias::HTTP_UNAUTHORIZED);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong'], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function show($id)
    {
        $product = Product::find($id);
        return response()->json($product, ResponseAlias::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function edit(ProductRequest $request, $id)
    {
        try {
            $current_user = Auth::user();
            if($current_user['is_admin']) {
                $product = Product::find($id);
                $product->name = $request->name;
                $product->description = $request->description;
                $product->image = $request->image;
                $product->discount = $request->discount;
                $product->quantity = $request->quantity;
                $product->category_id  = $request->category_id;
                $product->price = $request->price;
                $product->update();
                return response()->json($product, ResponseAlias::HTTP_OK);
            } else {
                return response()->json(['message' => 'You are not authorized to access this resource'], ResponseAlias::HTTP_UNAUTHORIZED);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong'], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            $current_user = Auth::user();
            if ($current_user['is_admin']) {
                $product = Product::find($id);
                $product->delete();
                return response()->json(['message' => 'Product deleted successfully'], ResponseAlias::HTTP_OK);
            } else {
                return response()->json(['message' => 'You are not authorized to access this resource'], ResponseAlias::HTTP_UNAUTHORIZED);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong'], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }
}
