<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\PromotionSearcher;
use Illuminate\Http\Request;

class MenuOverviewController extends Controller
{
    public function __invoke(Request $request)
    {
        $products = Product::whereHas('category')->with(['category', 'discount'])->get();
        $products = PromotionSearcher::assignPromotionsToProducts($products);

        $categories = Category::whereNull('parent_category_id')
                        ->when(!empty($request->category_id), function($query) use ($request) {
                            $query->where('id', '!=', $request->category_id);
                        })
                        ->get();

        // Manually remove unnecessary category to return
        $finalCategories = (new Category)->getChildrenHierarchically($categories, $request->category_id ?? null, null, $request->max_level ?? null);

        $finalCategories = $this->flattenCategories($finalCategories);

        foreach($finalCategories as $finalCategory) {
            $finalCategory->products = $products->where('product_category_id', $finalCategory->id);
        }

        return response()->json([
            'error' => false,
            'categories' => $finalCategories
        ]);
    }

    protected function flattenCategories($categories, $level = 0) {
        $flattenedCategories = [];
    
        foreach ($categories as $category) {
            $category['level'] = $level;
            $prefix = str_repeat("-", $category['level']);
    
            $category['category_label'] = $prefix . " " . $category['name'] . " (" . $category['code'] . ")";
            $flattenedCategories[] = $category;
    
            if (!empty($category['children'])) {
                $flattenedCategories = array_merge($flattenedCategories, $this->flattenCategories($category['children'], $level + 1));
            }
        }
    
        return $flattenedCategories;
    }
}
