<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = $this->productService->getProducts($request->all());

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
        $product = $this->productService->createProduct($request->all());

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
        $product = $this->productService->findProduct($id, true);

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
        try {
            $this->productService->updateProduct($id, $request->except('_method'));
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'error_message' => $e->getMessage()
            ]);
        }

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
        try {
            $this->productService->deleteProduct($id);
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'error_message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'error' => false,
            'message' => 'Product deleted successfully'
        ]);
    }
}
