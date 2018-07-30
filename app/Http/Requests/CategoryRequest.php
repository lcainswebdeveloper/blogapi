<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Category;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|unique:categories,title'
        ];
    }

    public function createNew(){
        $category = new Category;
        $category->title = $title = $this->title;
        $category->slug = slugify($title);
        $category->user_id = auth()->id();
        $category->save();
        return $category;
    }
}
