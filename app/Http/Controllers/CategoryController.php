<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Requests\Category\Request as CategoryRequest;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->model = Category::class;
        $this->validator = CategoryRequest::class;
    }

    protected function beforeDeleteActions($post)
    {
        if (!is_null($post->posts())) {
            $post->posts()->detach();
        }
        return $post;
    }
}
