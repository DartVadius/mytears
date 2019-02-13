<?php

namespace App\Repositories\Category;

use App\Repositories\BaseRepository;


class CategoryRepository extends BaseRepository
{
    /**
     * @param $categoryId integer
     * @param $withChildren string
     * @return mixed
     */
    public function getCategory($categoryId, $withChildren)
    {
        if ($withChildren === 'true') {
            return $withChildren;
        }
        return $this->findById($categoryId);
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        return $this->findAll();
    }
}
