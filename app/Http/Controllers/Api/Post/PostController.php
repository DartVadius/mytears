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
use Cache;
use Illuminate\Support\Facades\Auth;

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

        Cache::tags(['posts_selection'])->flush();

        return response()->json(['response' => new PostResource($post)], Response::HTTP_OK);
    }

    public function updatePost(UpdatePost $request)
    {
        $validatedData = $request->validated();
        $post = $this->postService->updateEntity($validatedData);

        Cache::forget('post_' . $post->getId());
        if (array_key_exists('publish', $validatedData)) {
            Cache::tags(['posts_selection'])->flush();
        }

        return response()->json(['response' => new PostResource($post)], Response::HTTP_OK);
    }

    public function getPost(GetPost $request, $post_id = null)
    {
        if ($request->user('api') && $request->user('api')->isAdmin()) {
            return $this->getPostAdmin($request, $post_id);
        }
        return $this->getPostUser($request, $post_id);
    }

    private function getPostUser(GetPost $request, $post_id = null)
    {
        if ($post_id) {
            $result = Cache::rememberForever('post_' . $post_id, function () use ($post_id, $request) {
                return (new PostResource($this->postService->getPost($post_id, false)))->toArray($request);
            });

            return response()->json(['response' => $result], Response::HTTP_OK);
        }
        $validatedData = $request->validated();
        $tagId = null;
        if (isset($validatedData['tag']) && $tag = $validatedData['tag']) {
            $tagId = explode(',', $tag);
        }

        $key = 'page_' . ($validatedData['page'] ?? null) . '_limit_' . ($validatedData['limit'] ?? null) . '_category_'
            . ($validatedData['category'] ?? null) . '_tag_' . ((is_array($tagId)) ? implode('_', $tagId) : null);

        $result = Cache::tags(['posts_selection'])->rememberForever($key, function () use ($validatedData, $tagId, $request) {
            $response = $this->postService->getPosts($validatedData['page'] ?? null,
                $validatedData['limit'] ?? null,
                $validatedData['category'] ?? null, $tagId, false);
            $collection = new Collection($response['results']);
            return [
                'response' => (PostsCollection::collection($collection))->toArray($request),
                'limit' => $response['limit'],
                'pages'=> $response['pages'],
                'page'=> $response['page'],
                'links' => $response['links'],
            ];
        });

        return response()->json($result, Response::HTTP_OK);
    }

    private function getPostAdmin(GetPost $request, $post_id = null)
    {
        if ($post_id) {
            return response()->json(['response' => new PostResource($this->postService->getPost($post_id, true))], Response::HTTP_OK);
        }
        $validatedData = $request->validated();
        $tagId = null;
        if (isset($validatedData['tag']) && $tag = $validatedData['tag']) {
            $tagId = explode(',', $tag);
        }

        $response = $this->postService->getPosts($validatedData['page'] ?? null,
            $validatedData['limit'] ?? null,
            $validatedData['category'] ?? null,
            $tagId, true);
        $collection = new Collection($response['results']);

        return response()->json([
            'response' => (PostsCollection::collection($collection)),
            'limit' => $response['limit'],
            'pages'=> $response['pages'],
            'page'=> $response['page'],
            'links' => $response['links'],
        ], Response::HTTP_OK);
    }

    public function deletePost($post_id)
    {
        $this->postService->deleteEntity($post_id);

        Cache::forget('post_' . $post_id);

        return response()->json(null, Response::HTTP_OK);
    }

    public function restorePost($post_id)
    {
        $result = $this->postService->restoreDeletedEntity($post_id);

        return response()->json(['response' => new PostResource($result)], Response::HTTP_OK);
    }

    public function getDeletedPosts()
    {
        $result = $this->postService->getDeletedEntities();
        $collection = new Collection($result);

        return response()->json(['response' => PostsCollection::collection($collection)], Response::HTTP_OK);
    }
}