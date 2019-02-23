<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePost extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|max:10',
            'title' => 'string|max:255|unique:App\Entities\Post,title,' . $this->get('id'),
            'slug' => 'string|max:255|unique:App\Entities\Post,slug,' . $this->get('id'),
            'meta_title' => 'max:255|nullable',
            'meta_keywords' => 'max:255|nullable',
            'meta_description' => 'max:255|nullable',
            'category_id' => 'integer|nullable',
            'short_text' => 'string|nullable',
            'full_text' => 'string',
            'publish' => 'integer|between:0,1',
            'order' => 'integer|nullable',
            'tags' => 'array',
        ];
    }
}
