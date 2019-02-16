<?php
/**
 * Created by PhpStorm.
 * User: dartvadius
 * Date: 16.02.19
 * Time: 15:54
 */

namespace App\Http\Controllers\Api\Post;

use App\Http\Requests\Post\CreatePost;
use App\Http\Requests\Post\UpdatePost;
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

    public function getPost(Request $request, $post_id = null)
    {

    }
}