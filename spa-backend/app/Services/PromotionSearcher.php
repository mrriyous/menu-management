<?php
namespace App\Services;

use App\Models\Discount;

class PromotionSearcher {

    /**
     * Assign promotions to products collection
     *
     * @param App\Models\Products $products
     * @return App\Models\Products
     */
    public static function assignPromotionsToProducts($products) {
        // get default promotion for all
        $defaultDiscount = Discount::where('discount_type', 'All')->orderBy('id', 'desc')->first();

        foreach($products as $product) {
            $product->promotion = self::getPromotion($product, $defaultDiscount);
        }

        return $products;
    }

    /**
     * Get promotion from item level, category and all level
     *
     * @param $product
     * @param $defaultDiscount
     * @return void
     */
    public static function getPromotion($product, $defaultDiscount) {
        $promotion = $product->discount;

        if (empty($promotion)) {
            $promotion = self::getPromotionFromCategory($product);
        }

        if (empty($promotion)) {
            $promotion = $defaultDiscount;
        }

        return $promotion;
    }

    /**
     * Get promotion from category
     *
     * @param $product
     * @return void
     */
    protected static function getPromotionFromCategory($product) {
        return $product->category->getElligibleDiscount();
    }
}