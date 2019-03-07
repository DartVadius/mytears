<?php
/**
 * Created by PhpStorm.
 * User: dartvadius
 * Date: 16.02.19
 * Time: 15:57
 */

namespace App\Services\Post;


use App\Entities\Category;
use App\Entities\Post;
use App\Entities\Tag;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Tag\TagRepository;
use App\Repositories\Post\PostRepository;
use Illuminate\Http\Response;

class PostService
{
    private $postRepository;
    private $tagRepository;
    private $categoryRepository;

    public function __construct(PostRepository $postRepository, TagRepository $tagRepository, CategoryRepository $categoryRepository)
    {
        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function getPosts($page, $limit, $categoryId, $tag)
    {
        return $this->postRepository->getPaginated($page, $limit, $categoryId, $tag);
    }

    public function getPost($postId)
    {
        if (!$post = $this->postRepository->findById($postId)) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Wrong post id.');
        }
        return $post;
    }

    public function createEntity(array $data)
    {
        $post = new Post();
        $post->setTitle($data['title'])
            ->setShortText($data['short_text'])
            ->setFullText($data['full_text'])
            ->setPublish($data['publish'])
            ->setOrder($data['order'])
            ->setMetaTitle($data['meta_title'])
            ->setMetaKeywords($data['meta_keywords'])
            ->setMetaDescription($data['meta_description']);
        if (!empty($data['category_id'])) {
            /** @var $category Category */
            if (!$category = $this->categoryRepository->getCategory($data['category_id'])) {
                abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Wrong category id.');
            }
            $post->setCategory($category);
        }
        if (is_array($data['tags'])) {
            foreach ($data['tags'] as $tagId) {
                /** @var $tag Tag * */
                if ($tag = $this->tagRepository->findById($tagId)) {
                    $post->addTag($tag);
                }
            }
        }
        return $this->save($post);
    }

    public function deleteEntity($post_id)
    {
        /**@var $post Post */
        if (!$post = $this->getPost($post_id)) {

            return abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Wrong post Id');
        }
        $this->postRepository->delete($post);

        return null;
    }

    public function restoreDeletedEntity($id)
    {
        // restore soft deleted entities
        $this->postRepository->getEntityManager()->getFilters()->disable('soft-deleteable');
        /**@var $post Post */
        $post = $this->postRepository->findById($id);
        if ($post) {
            $post->setDeletedAt(null);
            $this->save($post);
        }

        return $post;
    }

    public function getDeletedEntities()
    {
        return $this->postRepository->getDeletedPosts();
    }

    public function updateEntity(array $data)
    {
        /** @var $post Post */
        if (!$post = $this->postRepository->findById($data['id'])) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Wrong post Id.');
        }

        if (array_key_exists('title', $data)) {
            $post->setTitle($data['title']);
        }
        if (array_key_exists('slug', $data)) {
            $post->setSlug($data['slug']);
        }
        if (array_key_exists('short_text', $data)) {
            $post->setShortText($data['short_text']);
        }
        if (array_key_exists('full_text', $data)) {
            $post->setFullText($data['full_text']);
        }
        if (array_key_exists('publish', $data)) {
            $post->setPublish($data['publish']);
        }
        if (array_key_exists('order', $data)) {
            $post->setOrder($data['order']);
        }
        if (array_key_exists('meta_title', $data)) {
            $post->setMetaTitle($data['meta_title']);
        }
        if (array_key_exists('meta_keywords', $data)) {
            $post->setMetaKeywords($data['meta_keywords']);
        }
        if (array_key_exists('meta_description', $data)) {
            $post->setMetaDescription($data['meta_description']);
        }

        if (array_key_exists('category_id', $data)) {
            /** @var $category Category */
            if ($category = $this->categoryRepository->findById($data['category_id'])) {
                $post->setCategory($category);
            } else {
                $post->removeCategory();
            }
        }

        if (array_key_exists('tags', $data)) {
            $post->removeAllTags();
            if (count($data['tags']) > 0) {
                foreach ($data['tags'] as $tagId) {
                    /** @var $tag Tag */
                    if ($tag = $this->tagRepository->findById($tagId)) {
                        $post->addTag($tag);
                    }
                }
            }
        }

        return $this->save($post);
    }

    private function save(Post $post)
    {
        try {
            $this->postRepository->save($post);
        } catch (\Exception $exception) {
            abort(Response::HTTP_EXPECTATION_FAILED, $exception->getMessage());
        }
        return $post;
    }
}