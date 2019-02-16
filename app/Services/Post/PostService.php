<?php
/**
 * Created by PhpStorm.
 * User: dartvadius
 * Date: 16.02.19
 * Time: 15:57
 */

namespace App\Services\Post;


use App\Repositories\Post\PostRepository;

class PostService
{
    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
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