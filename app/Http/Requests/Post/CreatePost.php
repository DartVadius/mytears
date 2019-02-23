<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class CreatePost extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'string|required|unique:App\Entities\Post,title|max:255',
            'meta_title' => 'max:255',
            'meta_keywords' => 'max:255',
            'meta_description' => 'max:255',
            'category_id' => 'integer|nullable',
            'short_text' => 'string|nullable',
            'full_text' => 'string|required',
            'publish' => 'integer',
            'order' => 'integer',
            'tags' => 'array',
        ];
    }
}
