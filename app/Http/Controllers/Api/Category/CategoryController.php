<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Requests\Category\CreateCategory;
use App\Http\Requests\Category\UpdateCategory;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\Collections\CategoriesCollection;
use App\Services\Category\CategoryService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class CategoryController extends Controller
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function getCategory(Request $request, $category_id = null)
    {
        if ($category_id) {
            $result = $this->categoryService->getCategory($category_id, $request->get('children'));
            if ($request->get('children') === 'true') {
                $collection = new Collection($result);

                return response()->json(['response' => CategoriesCollection::collection($collection)], Response::HTTP_OK);
            }

            return response()->json(['response' => new CategoryResource($result)], Response::HTTP_OK);
        }
        $result = $this->categoryService->getCategories();
        $collection = new Collection($result);

        return response()->json(['response' => CategoriesCollection::collection($collection)], Response::HTTP_OK);
    }

    public function createCategory(CreateCategory $request)
    {
        $validatedData = $request->validated();
        $category = $this->categoryService->createEntity($validatedData);
        return response()->json(['response' => new CategoryResource($category)], Response::HTTP_OK);
    }

    public function updateCategory(UpdateCategory $request)
    {
        $validatedData = $request->validated();
        $category = $this->categoryService->updateEntity($validatedData);
        return response()->json(['response' => new CategoryResource($category)], Response::HTTP_OK);
    }
}
