<?php

namespace App\Repositories\Category;

use App\Repositories\BaseRepository;


class CategoryRepository extends BaseRepository
{
    public function getCategory($categoryId, $withChildren)
    {
        if ($categoryId) {
            return $this->getCategoryById($categoryId, $withChildren);
        }
        return $this->findAll();
    }

    private function getCategoryById($categoryId, $withChildren)
    {
        if ($withChildren === 'true') {
            return $withChildren;
        }
        return $this->findById($categoryId);
    }
}
