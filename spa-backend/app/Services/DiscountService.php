<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Repositories\CategoryRepository;
use App\Repositories\DiscountRepository;
use App\Repositories\ProductRepository;
use Exception;

class DiscountService
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

    public function getDiscounts(array $filter)
    {
        return $this->discountRepository->getDiscounts($filter);
    }

    public function createDiscount(array $data)
    {
        $errors = [];
        $arrayToSave = $data;
        if ($data['discount_type'] != 'All') {
            $repo = $data['discount_type'] == 'Category' ? $this->categoryRepository : $this->productRepository;
            $fieldToUse = $data['discount_type'] == 'Category' ? 'product_category_id' : 'product_id';
            $idToCheck = $data['discount_type'] == 'Category' ? $data[$fieldToUse] : $data[$fieldToUse];

            $findData = $repo->find($idToCheck);

            if (empty($findData)) {
                // Authentication failed
                $errors[$fieldToUse] = ['Please select the valid data.'];
            }

            $arrayToSave['discountable_type'] = $repo->getModelClass();
            $arrayToSave['discountable_id'] = $idToCheck;
        }

        if (!empty($errors)) {
            return [
                'error' => true,
                'errors' => $errors
            ];
        }

        return ['error' => false, 'discount' => $this->discountRepository->createDiscount($arrayToSave)];
    }

    public function findDiscount($id, $withParent = false)
    {
        return $this->discountRepository->findDiscount($id, $withParent);
    }

    public function updateDiscount($id, array $data)
    {
        $category = $this->findDiscount($id);

        if (!$category) {
            throw new Exception('Discount not found');
        }

        $errors = [];
        $arrayToSave = $data;
        if ($data['discount_type'] != 'All') {
            $repo = $data['discount_type'] == 'Category' ? $this->categoryRepository : $this->productRepository;
            $fieldToUse = $data['discount_type'] == 'Category' ? 'product_category_id' : 'product_id';
            $idToCheck = $data['discount_type'] == 'Category' ? $data[$fieldToUse] : $data[$fieldToUse];

            $findData = $repo->find($idToCheck);

            if (empty($findData)) {
                // Authentication failed
                $errors[$fieldToUse] = ['Please select the valid data.'];
            }

            $arrayToSave['discountable_type'] = $repo->getModelClass();
            $arrayToSave['discountable_id'] = $idToCheck;
        }

        unset($arrayToSave['product_category_id']);
        unset($arrayToSave['product_id']);
        unset($arrayToSave['_method']);

        if (!empty($errors)) {
            return [
                'error' => true,
                'errors' => $errors
            ];
        }

        return ['error' => false, 'discount' => $this->discountRepository->updateDiscount($id, $arrayToSave)];
    }

    public function deleteDiscount($id)
    {
        $category = $this->findDiscount($id);

        if (!$category) {
            throw new Exception('Discount not found');
        }

        return $this->discountRepository->deleteDiscount($id);
    }
}