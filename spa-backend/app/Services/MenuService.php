<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Repositories\DiscountRepository;
use App\Repositories\ProductRepository;
use Exception;

class MenuService
{
    protected $discountRepository;
    protected $categoryRepository;
    protected $productRepository;

    public function __construct(DiscountRepository $discountRepository, CategoryRepository $categoryRepository, ProductRepository $productRepository)
    {
        $this->discountRepository = $discountRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    public function getMenuOverview(array $data) {
        $products = $this->productRepository->getProductsForMenuOverview();

        $products = PromotionSearcher::assignPromotionsToProducts($products);

        $categories = $this->categoryRepository->getParentCategories($data);

        // Manually remove unnecessary category to return
        $finalCategories = $this->categoryRepository->getParentCategoryChildren($categories, $data);

        $finalCategories = $this->flattenCategories($finalCategories);

        foreach($finalCategories as $finalCategory) {
            $finalCategory->products = $products->where('product_category_id', $finalCategory->id);
        }

        return $finalCategories;
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