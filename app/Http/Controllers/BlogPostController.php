<?php

namespace App\Http\Controllers;

use App\BlogPost;
use Illuminate\Http\Request;
use App\Http\Requests\BlogPost\Request as BlogPostRequest;

class BlogPostController extends Controller
{
    public function __construct()
    {
        $this->model = BlogPost::class;
        $this->validator = BlogPostRequest::class;
    }

    protected function beforeDeleteActions($post)
    {
        if (!is_null($post->categories())) {
            $post->categories()->detach();
        }
        return $post;
    }
}
