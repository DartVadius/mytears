<?php
/**
 * Created by PhpStorm.
 * User: dartvadius
 * Date: 16.02.19
 * Time: 15:54
 */

namespace App\Http\Controllers\Api\Tag;

use App\Http\Requests\Tag\CreateTag;
use App\Http\Requests\Tag\UpdateTag;
use App\Http\Resources\Collections\TagsCollection;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;
use App\Services\Tag\TagService;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class TagController extends Controller
{
    private $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function createTag(CreateTag $request)
    {
        $validatedData = $request->validated();
        $tag = $this->tagService->createEntity($validatedData);

        return response()->json(['response' => new TagResource($tag)], Response::HTTP_OK);
    }

    public function updateTag(UpdateTag $request)
    {
        $validatedData = $request->validated();
        $tag = $this->tagService->updateEntity($validatedData);

        return response()->json(['response' => new TagResource($tag)], Response::HTTP_OK);
    }

    public function getTag($tag_id = null)
    {
        if ($tag_id) {
            $result = $this->tagService->getTag($tag_id);

            return response()->json(['response' => new TagResource($result)], Response::HTTP_OK);
        }

        $result = $this->tagService->getTags();
        $collection = new Collection($result);

        return response()->json(['response' => TagsCollection::collection($collection)], Response::HTTP_OK);
    }

    public function deleteTag($tag_id)
    {
        $this->tagService->deleteEntity($tag_id);

        return response()->json(null, Response::HTTP_OK);
    }
}