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
            return $result;
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

    /**
     * @return mixed
     */
    public function getDeletedCategories()
    {
        $this->getEntityManager()->getFilters()->disable('soft-deleteable');
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('c')->from(Category::class, 'c')->where('c.deletedAt IS NOT NULL')->getQuery();

        return $query->getResult();
    }
}
