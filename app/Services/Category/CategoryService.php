<?php
/**
 * Created by PhpStorm.
 * User: dartvadius
 * Date: 10.02.19
 * Time: 12:41
 */

namespace App\Services\Category;


use App\Entities\Category;
use App\Repositories\Category\CategoryRepository;
use Illuminate\Http\Response;

class CategoryService
{

    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategory($category_id, $children)
    {
        return $this->categoryRepository->getCategory($category_id, $children);
    }

    public function getCategories()
    {
        return $this->categoryRepository->getCategories();
    }

    public function createEntity(array $data)
    {
        $category = new Category();
        $category->setTitle($data['title'])
            ->setMetaTitle($data['meta_title'])
            ->setMetaKeywords($data['meta_keywords'])
            ->setMetaDescription($data['meta_description']);
        if ($data['parent_id']) {
            $category = $this->updateParent($data['parent_id'], $category);
        }
        return $this->save($category);
    }

    public function updateEntity(array $data)
    {
        /**@var $category Category **/
        if (!$category = $this->getCategory($data['id'], 'false'))
        {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Wrong category Id');
        }

        if (array_key_exists('title', $data)) {
            $category->setTitle($data['title']);
        }
        if (array_key_exists('slug', $data)) {
            $category->setSlug($data['slug']);
        }
        if (array_key_exists('meta_title', $data)) {
            $category->setMetaTitle($data['meta_title']);
        }
        if (array_key_exists('meta_keywords', $data)) {
            $category->setMetaKeywords($data['meta_keywords']);
        }
        if (array_key_exists('meta_description', $data)) {
            $category->setMetaDescription($data['meta_description']);
        }
        if (array_key_exists('parent_id', $data)) {
            $category = $this->updateParent($data['parent_id'], $category);
        }
        return $this->save($category);
    }

    /**
     * @param $parentId
     * @param $category Category
     * @return mixed
     */
    private function updateParent($parentId, $category)
    {
        if (!$parentId) {
            return $category->removeParent();
        }
        /**@var $parentCategory Category **/
        if (!$parentCategory = $this->getCategory($parentId, 'false'))
        {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Wrong parent Id');
        }
        $category->setParent($parentCategory);
        return $category;
    }

    private function save(Category $category)
    {
        try {
            $this->categoryRepository->save($category);
        } catch (\Exception $exception) {
            abort(Response::HTTP_EXPECTATION_FAILED, $exception->getMessage());
        }
        return $category;
    }
}