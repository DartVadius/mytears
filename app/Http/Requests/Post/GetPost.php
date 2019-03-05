<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class GetPost extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'page' => 'integer|nullable',
            'limit' => 'integer|nullable',
            'category' => 'integer|nullable',
            'tag' => 'string|nullable'
        ];
    }
}
