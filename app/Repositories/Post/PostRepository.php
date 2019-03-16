<?php

namespace App\Repositories\Post;

use App\Entities\Post;
use App\Repositories\BaseRepository;


class PostRepository extends BaseRepository
{
    public function getPaginated($page, $limit, $categoryId, $tagId, $withUnpublished)
    {
        $page = $page ? $page : Post::PAGE;
        $limit = $limit ? $limit : Post::LIMIT;

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p')->from(Post::class, 'p');

        $link = '/api/posts?';
        if ($categoryId) {
            $link .= 'category=' . $categoryId . '&';
            $qb->andWhere('p.category = :categoryId')->setParameter('categoryId', $categoryId);
        }
        if ($tagId) {
            $link .= 'tag=' . implode(',', $tagId) . '&';
            $qb->andWhere(':tagId MEMBER OF p.tags')->setParameter('tagId', $tagId);
        }

        if (!$withUnpublished) {
            $qb->andWhere('p.publish = :publish')->setParameter('publish', Post::PUBLISHED_TRUE);
        }

        $count = count($qb->getQuery()->getResult());
        $pages = ceil($count / $limit);

        $qb->orderBy('p.order', 'ASC')->addOrderBy('p.createdAt', 'DESC');

        $qb->setFirstResult($limit * ($page - 1))->setMaxResults($limit);

        $query = $qb->getQuery();

        $link .= 'limit=' . $limit  . '&';

        return [
            'results' => $query->getResult(),
            'limit' => (int)$limit,
            'pages' =>(int) $pages,
            'page' => (int)$page,
            'links' => [
                'prev' => ((int)$page > 1 && (($pages - (int)$page) >= 0)) ? $link . 'page=' . ($page - 1) : null,
                'next' => ((int)$page < $pages) ? $link . 'page=' . ($page + 1) : null,
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function getDeletedPosts()
    {
        $this->getEntityManager()->getFilters()->disable('soft-deleteable');
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('c')->from(Post::class, 'c')->where('c.deletedAt IS NOT NULL')->getQuery();

        return $query->getResult();
    }

    public function findById($id, $withUnpublished = false)
    {
        /**@var $post Post*/
        if ($post = parent::findById($id)) {
//            print_r($post->all());
            if (!$post->isPublish() && !$withUnpublished) {
                $post = null;
            }
        }
        return $post;
    }
}
