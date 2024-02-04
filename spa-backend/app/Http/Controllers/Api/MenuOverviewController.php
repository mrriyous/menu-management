<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\MenuService;
use App\Services\PromotionSearcher;
use Illuminate\Http\Request;

class MenuOverviewController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function __invoke(Request $request)
    {
        $finalCategories = $this->menuService->getMenuOverview($request->all());

        return response()->json([
            'error' => false,
            'categories' => $finalCategories
        ]);
    }
}
