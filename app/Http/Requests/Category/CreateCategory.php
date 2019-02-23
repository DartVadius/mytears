<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class CreateCategory extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'string|required|unique:App\Entities\Category,title|max:255',
            'meta_title' => 'max:255',
            'meta_keywords' => 'max:255',
            'meta_description' => 'max:255',
            'parent_id' => 'integer|nullable',
        ];
    }
}
