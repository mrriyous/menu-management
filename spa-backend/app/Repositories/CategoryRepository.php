<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{

    public function getModelClass()
    {
        return Category::class;
    }

    public function getCategories(array $filterQuery = [])
    {
        $categories = Category::orderBy('code', 'asc')->with('parent');
        
        if (!empty($filterQuery['search'])) {
            $categories = $categories->where('name', 'like', '%'.$filterQuery['search'].'%')->orWhere('name', 'like', '%'.$filterQuery['search'].'%');
        }

        return $categories->paginate(20);
    }

    public function getParentCategories(array $filterQuery = [])
    {
        return Category::whereNull('parent_category_id')
                        ->when(!empty($filterQuery['category_id']), function($query) use ($filterQuery) {
                            $query->where('id', '!=', $filterQuery['category_id']);
                        })->get();
    }

    public function getParentCategoryChildren($parentCategories, array $filterQuery = [])
    {
        return (new Category)->getChildrenHierarchically($parentCategories, $filterQuery['category_id'] ?? null, null, $filterQuery['max_level'] ?? null);
    }

    public function createCategory(array $data)
    {
        return Category::create($data);
    }

    public function find($id)
    {
        return $this->findCategory($id);
    }

    public function findCategory($id, $withParent = false)
    {
        $category = new Category;

        if ($withParent) {
            $category = $category->with('parent');
        }

        return $category->find($id);
    }

    public function updateCategory($id, array $data)
    {
        return Category::where('id', $id)->update($data);
    }

    public function deleteCategory($id)
    {
        return Category::where('id', $id)->delete();
    }

}