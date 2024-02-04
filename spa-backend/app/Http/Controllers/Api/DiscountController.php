<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DiscountRequest;
use App\Http\Requests\Api\ProductRequest;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use App\Services\DiscountService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DiscountController extends Controller
{
    protected $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $discounts = $this->discountService->getDiscounts($request->all());

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

        $createResponse = $this->discountService->createDiscount($arrayToSave);

        if ($createResponse['error']) {
            throw ValidationException::withMessages($createResponse['errors']);
        }

        return response()->json([
            'error' => false,
            'discount' => $createResponse['discount']
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
        $discount = $this->discountService->findDiscount($id);

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

        $arrayToSave = $request->all();
        try {
            $updateResponse = $this->discountService->updateDiscount($id, $arrayToSave);
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'error_message' => $e->getMessage()
            ]);
        }

        if ($updateResponse['error']) {
            throw ValidationException::withMessages($updateResponse['errors']);
        }

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
        try {
            $this->discountService->deleteDiscount($id);
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'error_message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'error' => false,
            'message' => 'Discount deleted successfully'
        ]);
    }
}
