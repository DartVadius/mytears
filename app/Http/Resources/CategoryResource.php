<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $response = parent::toArray($request);
        $response['parent_id'] = null;
        if ($parent = $this->getParent()) {
            $response['parent_id'] = $parent->getId();
        }
        return $response;
    }
}
