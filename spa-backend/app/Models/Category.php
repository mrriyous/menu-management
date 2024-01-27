<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $maxSub = 4;

    protected $fillable = [
        'name',
        'code',
        'parent_category_id',
        'parent_category_id_2',
        'parent_category_id_3',
        'parent_category_id_4',
    ];

    public function directChildren() {
        return $this->hasMany(Category::class, 'parent_category_id', 'id');
    }

    public function children()
    {
        return $this->directChildren()->with('children');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_category_id', 'id')->with('parent');
    }

    public function discount(): MorphOne
    {
        return $this->morphOne(Discount::class, 'discountable')->orderBy('id', 'desc');
    }

    public function getElligibleDiscount() {
        if (empty($this->discount) && !empty($this->parent)) {
            return $this->parent->getElligibleDiscount();
        }

        return $this->discount;
    }

    public function getChildrenHierarchically($categories, $categoryId = null, $level = 1, $maxSub = null) {
        $usedMaxSub = !empty($maxSub) ? $maxSub : $this->maxSub;

        if ($level < $usedMaxSub) {
            foreach ($categories as $category) {
                $childrens = $category->directChildren()->with('parent')->when(!empty($categoryId), function($query) use ($categoryId) {
                                            $query->where('id', '!=', $categoryId);
                                        })->get();
                $category->children = $this->getChildrenHierarchically($childrens, $categoryId, $level + 1, $usedMaxSub);
            }
        }

        return $categories;
    }

}
