<?php

namespace App\Http\Controllers\Api\Category;

use App\Entities\Category;
use App\Http\Resources\CategoryResource;
use App\Services\Category\CategoryService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use phpDocumentor\Reflection\Types\Boolean;

class CategoryController extends Controller
{
    private $categoryService;
    private $request;

    public function __construct(CategoryService $categoryService, Request $request)
    {
        $this->categoryService = $categoryService;
        $this->request = $request;
    }

    public function getCategory($category_id = null)
    {
        $result = $this->categoryService->getCategory($category_id, $this->request->get('children'));
        return response()->json(['response' => $result], Response::HTTP_OK);
    }

    public function createCategory()
    {
        $data = $this->request->all();
        $category = $this->categoryService->createEntity($data);
        return response()->json(['response' => new CategoryResource($category)], Response::HTTP_OK);
    }

    public function updateCategory()
    {
        $data = $this->request->all();
        $category = $this->categoryService->updateEntity($data);
        return response()->json(['response' => new CategoryResource($category)], Response::HTTP_OK);
    }
}
