<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Exception;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getProducts(array $filter)
    {
        return $this->productRepository->getProducts($filter);
    }

    public function createProduct(array $data)
    {
        return $this->productRepository->createProduct($data);
    }

    public function findProduct($id, $withCategory = false)
    {
        return $this->productRepository->findProduct($id, $withCategory);
    }

    public function updateProduct($id, array $data)
    {
        $product = $this->findProduct($id);

        if (!$product) {
            throw new Exception('Product not found');
        }

        return $this->productRepository->updateProduct($id, $data);
    }

    public function deleteProduct($id)
    {
        $product = $this->findProduct($id);

        if (!$product) {
            throw new Exception('Product not found');
        }

        return $this->productRepository->deleteProduct($id);
    }
}