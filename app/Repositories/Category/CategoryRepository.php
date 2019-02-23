<?php

namespace App\Repositories\Category;

use App\Entities\Category;
use App\Repositories\BaseRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Illuminate\Http\Response;


class CategoryRepository extends BaseRepository
{
    /**
     * @param $categoryId
     * @param $withChildren
     * @return array|object|null
     */
    public function getCategory($categoryId, $withChildren = null)
    {
        if (!$result = $this->findById($categoryId))
        {
            return abort(Response::HTTP_NO_CONTENT);
        }
        if ($withChildren === 'true') {
            $rsm = new ResultSetMappingBuilder($this->getEntityManager());
            $rsm->addRootEntityFromClassMetadata(Category::class, 'm');
            $sql = 'WITH RECURSIVE MyTree AS (SELECT * FROM categories WHERE parent_id = :parentId
            UNION ALL SELECT m.* FROM categories AS m JOIN MyTree AS t ON m.parent_id = t.id) SELECT * FROM MyTree';
            $query = $this->_em->createNativeQuery($sql, $rsm);
            $query->setParameter('parentId', $result->getId());
            $categories = $query->getResult();
            $categories[] = $result;
            return $categories;
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        return $this->findAll();
    }
}
