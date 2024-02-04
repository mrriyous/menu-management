<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('code', 'asc')->with('parent');
        
        if (!empty($request->search)) {
            $categories = $categories->where('name', 'like', '%'.$request->search.'%')->orWhere('name', 'like', '%'.$request->search.'%');
        }

        $categories = $categories->paginate(20);

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
        $categories = Category::whereNull('parent_category_id')
                        ->when(!empty($request->category_id), function($query) use ($request) {
                            $query->where('id', '!=', $request->category_id);
                        })
                        ->get();

        // Manually remove unnecessary category to return
        $finalCategories = (new Category)->getChildrenHierarchically($categories, $request->category_id ?? null, null, $request->max_level ?? null);

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
        $category = Category::create($request->all());

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
        $category = Category::with('parent')->find($id);

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
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'error' => true,
                'error_message' => 'Category not found'
            ], 404);
        }

        $category->update($request->all());

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
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'error' => true,
                'error_message' => 'Category not found'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'error' => false,
            'message' => 'Category deleted successfully'
        ]);
    }
}
