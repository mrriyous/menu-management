<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::orderBy('code', 'asc')->with('category');

        if (!empty($request->search)) {
            $products = $products->where('name', 'like', '%'.$request->search.'%')->orWhere('name', 'like', '%'.$request->search.'%');
        }

        if ($request->paginate == 'no') {
            $products = $products->get();
        } else {
            $products = $products->paginate(20);
        }

        return response()->json([
            'error' => false,
            'products' => $products
        ]);
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $product = Product::create($request->all());

        return response()->json([
            'error' => false,
            'product' => $product
        ], 201);
    }

    /**
     * Display the specified product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json([
                'error' => true,
                'error_message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'product' => $product
        ]);
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'error' => true,
                'error_message' => 'Product not found'
            ], 404);
        }

        $product->update($request->all());

        return response()->json([
            'error' => false,
            'message' => "Successfully update the product"
        ]);
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'error' => true,
                'error_message' => 'Product not found'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'error' => false,
            'message' => 'Product deleted successfully'
        ]);
    }
}
