<?php
/**
 * Created by PhpStorm.
 * User: dartvadius
 * Date: 16.02.19
 * Time: 15:54
 */

namespace App\Http\Controllers\Api\Post;

use App\Entities\Post;
use App\Http\Requests\Post\CreatePost;
use App\Http\Requests\Post\GetPost;
use App\Http\Requests\Post\UpdatePost;
use App\Http\Resources\Collections\PostsCollection;
use App\Http\Resources\PostResource;
use App\Services\Post\PostService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class PostController extends Controller
{
    private $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function createPost(CreatePost $request)
    {
        $validatedData = $request->validated();
        $post = $this->postService->createEntity($validatedData);

        return response()->json(['response' => new PostResource($post)], Response::HTTP_OK);
    }

    public function updatePost(UpdatePost $request)
    {
        $validatedData = $request->validated();
        $post = $this->postService->updateEntity($validatedData);

        return response()->json(['response' => new PostResource($post)], Response::HTTP_OK);
    }

    public function getPost(GetPost $request, $post_id = null)
    {
        if ($post_id) {
            $result = $this->postService->getPost($post_id);

            return response()->json(['response' => new PostResource($result)], Response::HTTP_OK);
        }
        $validatedData = $request->validated();
        // todo
        print_r($validatedData);
        die;
        $page = $request->get('page');
        $limit = $request->get('limit');
        $categoryId = $request->get('category');
        $tagId = null;
        if ($tag = $request->get('tag')) {
            $tagId = explode(',', $tag);
        }

        // todo валидация гет-параметров???

        $result = $this->postService->getPosts($page, $limit, $categoryId, $tagId);

        $collection = new Collection($result['results']);

        // todo добавить пагинацию в респонс
        return response()->json(['response' => PostsCollection::collection($collection)], Response::HTTP_OK);
    }
}