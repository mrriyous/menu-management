<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function getModelClass()
    {
        return Product::class;
    }

    public function getProducts(array $filterQuery = [])
    {
        $products = Product::orderBy('code', 'asc')->with('category');
        
        if (!empty($filterQuery['search'])) {
            $products = $products->where('name', 'like', '%'.$filterQuery['search'].'%')->orWhere('name', 'like', '%'.$filterQuery['search'].'%');
        }

        if (($filterQuery['paginate'] ?? 'yes') == 'no') {
            $products = $products->get();
        } else {
            $products = $products->paginate(20);
        }

        return $products;
    }
    
    public function getProductsForMenuOverview()
    {
        return Product::whereHas('category')->with(['category', 'discount'])->get();
    }

    public function createProduct(array $data)
    {
        return Product::create($data);
    }

    public function find($id)
    {
        $this->findProduct($id);
    }

    public function findProduct($id, $withCategory = false)
    {
        $product = new Product();

        if ($withCategory) {
            $product = $product->with('category');
        }

        return $product->find($id);
    }

    public function updateProduct($id, array $data)
    {
        return Product::where('id', $id)->update($data);
    }

    public function deleteProduct($id)
    {
        return Product::where('id', $id)->delete();
    }
}