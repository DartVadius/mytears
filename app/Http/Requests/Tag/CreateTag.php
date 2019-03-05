<?php

namespace App\Http\Requests\Tag;

use Illuminate\Foundation\Http\FormRequest;

class CreateTag extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'string|required|unique:App\Entities\Tag,title|max:255',
            'meta_title' => 'max:255',
            'meta_keywords' => 'max:255',
            'meta_description' => 'max:255'
        ];
    }
}
