<?php
/**
 * Created by PhpStorm.
 * User: dartvadius
 * Date: 16.02.19
 * Time: 15:57
 */

namespace App\Services\Post;


use App\Repositories\Category\TagRepository;

class TagService
{
    private $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function createEntity()
    {
        return null;
    }

    public function updateEntity ()
    {
        return null;
    }
}