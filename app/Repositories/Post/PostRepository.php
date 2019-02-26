<?php

namespace App\Repositories\Post;

use App\Entities\Post;
use App\Repositories\BaseRepository;


class PostRepository extends BaseRepository
{
    public function getPaginated($page, $limit, $categoryId, $tagId)
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

        $query = $qb->getQuery();
        $count = count($query->getArrayResult());
        $pages = ceil($count / $limit);

        $qb->setFirstResult($limit * ($page - 1))->setMaxResults($limit);
        $query = $qb->getQuery();

        $link .= 'limit=' . $limit  . '&';

        return [
            'results' => $query->getResult(),
            'limit' => $limit,
            'pages' => $pages,
            'links' => [
                'prev' => ((int)$page > 1 && (($pages - (int)$page) >= 0)) ? $link . 'page=' . ($page - 1) : null,
                'next' => ((int)$page < $pages) ? $link . 'page=' . ($page + 1) : null,
            ],
        ];
    }
}
