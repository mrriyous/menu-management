<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'discount_type',
        'discountable_type',
        'discountable_id',
        'discount_percentage'
    ];

    /**
     * Discounted
     *
     * @return void
     */
    public function discountable(): MorphTo {
        return $this->morphTo();
    }
}
