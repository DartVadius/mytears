<?php

namespace App\Repositories\Post;

use App\Entities\Category;
use App\Entities\Post;
use App\Repositories\BaseRepository;
use Symfony\Component\HttpFoundation\Response;


class PostRepository extends BaseRepository
{
    public function savePost (Post $post, $category_id, CategoryRepository $categoryRepository) {
        /**@var $category Category**/
        if (!$category = $categoryRepository->findOneBy(['id' => $category_id])) {
            abort(Response::HTTP_BAD_REQUEST, 'No category found with id ' . $category_id);
        }
        $post->setCategory($category);
        $this->save($post);
    }
}
