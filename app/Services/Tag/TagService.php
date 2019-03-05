<?php
/**
 * Created by PhpStorm.
 * User: dartvadius
 * Date: 16.02.19
 * Time: 15:57
 */

namespace App\Services\Tag;


use App\Entities\Tag;
use App\Repositories\Tag\TagRepository;
use Illuminate\Http\Response;

class TagService
{
    private $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function createEntity($data)
    {
        $tag = new Tag();
        $tag->setTitle($data['title']);
        if (array_key_exists('meta_title', $data)) {
            $tag->setMetaTitle($data['meta_title']);
        }
        if (array_key_exists('meta_keywords', $data)) {
            $tag->setMetaKeywords($data['meta_keywords']);
        }
        if (array_key_exists('meta_description', $data)) {
            $tag->setMetaDescription($data['meta_description']);
        }
        return $this->save($tag);
    }

    public function updateEntity($data)
    {
        /**@var $tag Tag */
        if (!$tag = $this->tagRepository->findById($data['id']))
        {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Wrong tag Id');
        }
        if (array_key_exists('title', $data)) {
            $tag->setTitle($data['title']);
        }
        if (array_key_exists('slug', $data)) {
            $tag->setSlug($data['slug']);
        }
        if (array_key_exists('meta_title', $data)) {
            $tag->setMetaTitle($data['meta_title']);
        }
        if (array_key_exists('meta_keywords', $data)) {
            $tag->setMetaKeywords($data['meta_keywords']);
        }
        if (array_key_exists('meta_description', $data)) {
            $tag->setMetaDescription($data['meta_description']);
        }
        return $this->save($tag);
    }

    public function getTag($tagId)
    {
        /**@var $tag Tag */
        if (!$tag = $this->tagRepository->findById($tagId))
        {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Wrong tag Id');
        }

        return $tag;
    }

    public function getTags()
    {
        return $this->tagRepository->findAll();
    }

    private function save(Tag $tag)
    {
        try {
            $this->tagRepository->save($tag);
        } catch (\Exception $exception) {
            abort(Response::HTTP_EXPECTATION_FAILED, $exception->getMessage());
        }
        return $tag;
    }
}