<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategory extends FormRequest
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
            'title' => 'string|max:255|unique:App\Entities\Category,title,' . $this->get('id'),
            'slug' => 'string|max:255|max:255|unique:App\Entities\Category,slug,' . $this->get('id'),
            'meta_title' => 'max:255|nullable',
            'meta_keywords' => 'max:255|nullable',
            'meta_description' => 'max:255|nullable',
            'parent_id' => 'integer|nullable',
        ];
    }
}
