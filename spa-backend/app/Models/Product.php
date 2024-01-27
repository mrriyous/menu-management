<?php

namespace App\Models;

use App\Services\PromotionSearcher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_category_id',
        'code',
        'name',
        'price'
    ];

    public function category() {
        return $this->belongsTo(Category::class, 'product_category_id');
    }

    public function discount(): MorphOne
    {
        return $this->morphOne(Discount::class, 'discountable')->orderBy('id', 'desc');
    }

    /**
     * Get elligible discount for the product from 
     *
     * @return void
     */
    public function getElligibleDiscount() {
        return $this->getElligibleDiscount();
    }
}
