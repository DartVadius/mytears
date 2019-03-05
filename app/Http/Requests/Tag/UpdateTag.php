<?php

namespace App\Http\Requests\Tag;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTag extends FormRequest
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
            'title' => 'string|required|unique:App\Entities\Tag,title|max:255',
            'slug' => 'string|max:255|max:255|unique:App\Entities\Tag,slug,' . $this->get('id'),
            'meta_title' => 'max:255',
            'meta_keywords' => 'max:255',
            'meta_description' => 'max:255'
        ];
    }
}
