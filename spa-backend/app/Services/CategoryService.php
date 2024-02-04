<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use Exception;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategories(array $filter)
    {
        return $this->categoryRepository->getCategories($filter);
    }

    public function getCategoryHierarchically(array $filter)
    {
        $categories = $this->categoryRepository->getParentCategories($filter);

        return $this->categoryRepository->getParentCategoryChildren($categories, $filter);
    }

    public function createCategory(array $data)
    {
        return $this->categoryRepository->createCategory($data);
    }

    public function findCategory($id, $withParent = false)
    {
        return $this->categoryRepository->findCategory($id, $withParent);
    }

    public function updateCategory($id, array $data)
    {
        $category = $this->findCategory($id);

        if (!$category) {
            throw new Exception('Category not found');
        }

        return $this->categoryRepository->updateCategory($id, $data);
    }

    public function deleteCategory($id)
    {
        $category = $this->findCategory($id);

        if (!$category) {
            throw new Exception('Category not found');
        }

        return $this->categoryRepository->deleteCategory($id);
    }
}