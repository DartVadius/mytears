<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoriesCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $response['id'] = $this->getId();
        $response['title'] = $this->getTitle();
        $response['slug'] = $this->getSlug();
        $response['metaTitle'] = $this->getMetaTitle();
        $response['metaKeywords'] = $this->getMetaKeywords();
        $response['metaDescription'] = $this->getMetaDescription();
        $response['createdAt'] = $this->getCreatedAt();
        $response['updatedAt'] = $this->getUpdatedAt();
        $response['deletedAt'] = $this->getDeletedAt();

        $response['parent_id'] = null;
        if ($parent = $this->getParent()) {
            $response['parent_id'] = $parent->getId();
        }
        return $response;
    }
}
