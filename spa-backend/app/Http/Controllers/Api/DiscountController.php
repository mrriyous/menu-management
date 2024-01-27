<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DiscountRequest;
use App\Http\Requests\Api\ProductRequest;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DiscountController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $discounts = Discount::orderBy('code', 'asc')->with('discountable');

        if (!empty($request->search)) {
            $discounts = $discounts->where('name', 'like', '%'.$request->search.'%')->orWhere('name', 'like', '%'.$request->search.'%');
        }

        $discounts = $discounts->paginate(20);

        return response()->json([
            'error' => false,
            'discounts' => $discounts
        ]);
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DiscountRequest $request)
    {
        $arrayToSave = $request->all();

        if ($request->discount_type != 'All') {
            $model = $request->discount_type == 'Category' ? Category::class : Product::class;
            $fieldToUse = $request->discount_type == 'Category' ? 'product_category_id' : 'product_id';
            $idToCheck = $request->discount_type == 'Category' ? $request->{$fieldToUse} : $request->{$fieldToUse};
            $findData = $model::find($idToCheck);

            if (empty($findData)) {
                // Authentication failed
                throw ValidationException::withMessages([
                    $fieldToUse => ['Please select the valid data.'],
                ]);
            }

            $arrayToSave['discountable_type'] = $model;
            $arrayToSave['discountable_id'] = $idToCheck;
        }

        $discount = Discount::create($arrayToSave);

        return response()->json([
            'error' => false,
            'discount' => $discount
        ], 201);
    }

    /**
     * Display the specified discount.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $discount = Discount::find($id);

        if (!$discount) {
            return response()->json([
                'error' => true,
                'error_message' => 'Discount not found'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'discount' => $discount
        ]);
    }

    /**
     * Update the specified discount in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DiscountRequest $request, $id)
    {
        $discount = Discount::find($id);

        if (!$discount) {
            return response()->json([
                'error' => true,
                'error_message' => 'Discount not found'
            ], 404);
        }

        $arrayToSave = $request->all();
        if ($request->discount_type != 'All') {
            $model = $request->discount_type == 'Category' ? Category::class : Product::class;
            $fieldToUse = $request->discount_type == 'Category' ? 'product_category_id' : 'product_id';
            $idToCheck = $request->discount_type == 'Category' ? $request->{$fieldToUse} : $request->{$fieldToUse};
            $findData = $model::find($idToCheck);

            if (empty($findData)) {
                // Authentication failed
                throw ValidationException::withMessages([
                    $fieldToUse => ['Please select the valid data.'],
                ]);
            }

            $arrayToSave['discountable_type'] = $model;
            $arrayToSave['discountable_id'] = $idToCheck;
        }

        $discount->update($arrayToSave);

        return response()->json([
            'error' => false,
            'message' => "Successfully update the discount"
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
        $discount = Discount::find($id);

        if (!$discount) {
            return response()->json([
                'error' => true,
                'error_message' => 'Discount not found'
            ], 404);
        }

        $discount->delete();

        return response()->json([
            'error' => false,
            'message' => 'Discount deleted successfully'
        ]);
    }
}
