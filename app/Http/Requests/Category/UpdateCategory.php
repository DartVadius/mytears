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
            'title' => 'string|required|max:255unique:App\Entities\Category,title,' . $this->get('id'),
            'slug' => 'string|nullable|max:255',
            'meta_title' => 'max:255',
            'meta_keywords' => 'max:255',
            'meta_description' => 'max:255',
            'parent_id' => 'integer|max:10|nullable',
        ];
    }
}
