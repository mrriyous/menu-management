<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    /**
     * Display a listing of the categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = $this->categoryService->getCategories($request->all());

        return response()->json([
            'error' => false,
            'categories' => $categories
        ]);
    }

    /**
     * Get category
     *
     * @param Request $reqeuest
     * @return void
     */
    public function getHierarchically(Request $request) {

        // Manually remove unnecessary category to return
        $finalCategories = $this->categoryService->getCategoryHierarchically($request->all());
        
        

        return response()->json([
            'error' => false,
            'categories' => $finalCategories
        ]);
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $category = $this->categoryService->createCategory($request->all());

        return response()->json([
            'error' => false,
            'category' => $category
        ], 201);
    }

    /**
     * Display the specified category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = $this->categoryService->findCategory($id, true);

        if (!$category) {
            return response()->json([
                'error' => true,
                'error_message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'category' => $category
        ]);
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        try {
            $this->categoryService->updateCategory($id, $request->except('_method'));
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'error_message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'error' => false,
            'message' => "Successfully update the category"
        ]);
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->categoryService->deleteCategory($id);
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'error_message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'error' => false,
            'message' => 'Category deleted successfully'
        ]);
    }
}
