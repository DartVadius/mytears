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
        $tagId = null;
        if (isset($validatedData['tag']) && $tag = $validatedData['tag']) {
            $tagId = explode(',', $tag);
        }
        $result = $this->postService->getPosts(isset($validatedData['page']) ? $validatedData['page'] : null,
            isset($validatedData['limit']) ? $validatedData['limit'] : null,
            isset($validatedData['category']) ? $validatedData['category'] : null, $tagId);
        $collection = new Collection($result['results']);

        return response()->json([
            'response' => PostsCollection::collection($collection),
            'limit' => $result['limit'],
            'pages'=> $result['pages'],
            'page'=> $result['page'],
            'links' => $result['links'],
        ], Response::HTTP_OK);
    }
}