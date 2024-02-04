<?php

namespace App\Repositories;

use App\Models\Discount;

class DiscountRepository
{
    public function getDiscounts(array $filterQuery = [])
    {
        $discounts = Discount::orderBy('code', 'asc')->with('discountable');
        
        if (!empty($filterQuery['search'])) {
            $discounts = $discounts->where('name', 'like', '%'.$filterQuery['search'].'%')->orWhere('name', 'like', '%'.$filterQuery['search'].'%');
        }

        return $discounts->paginate(20);
    }

    public function createDiscount(array $data)
    {
        return Discount::create($data);
    }

    public function findDiscount($id)
    {
        return Discount::find($id);
    }

    public function updateDiscount($id, array $data)
    {
        return Discount::where('id', $id)->update($data);
    }

    public function deleteDiscount($id)
    {
        return Discount::where('id', $id)->delete();
    }
}