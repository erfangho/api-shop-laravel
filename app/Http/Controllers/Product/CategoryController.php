<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $categories = Category::all();

        return response()->json($categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        try {
            $current_user = Auth::user();
            if ($current_user['is_admin']) {
                $category = new Category();
                $category->name = $request->name;
                $category->save();

                return response()->json($category, ResponseAlias::HTTP_OK);
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
     * @param  \App\Models\Category  $category
     * @return JsonResponse
     */
    public function show($id)
    {
        $category = Category::find($id);

        return response()->json($category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function edit(Request $request, $id)
    {
        try {
            $current_user = Auth::user();
            if ($current_user['is_admin']) {
                $category = Category::find($id);
                $category->name = $request->name;
                $category->save();

                return response()->json($category);
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
     * @param  \App\Models\Category  $category
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            $current_user = Auth::user();
            if ($current_user['is_admin']) {
                $products = Product::query()->where('category_id', $id)->get();
                $products->each(function ($product) {
                    $product->delete();
                });

                $category = Category::find($id);
                $category->delete();

                return response()->json(['message' => 'Category deleted successfully'], ResponseAlias::HTTP_OK);
            } else {
                return response()->json(['message' => 'You are not authorized to access this resource'], ResponseAlias::HTTP_UNAUTHORIZED);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }
}
