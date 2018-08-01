<?php

namespace App;

class BlogPost extends BlogModel
{
    protected $orderBy = 'created_at';
    protected $orderDir = 'desc';

    public function loadRelationships($query){
        return $query->with(['user', 'categories']);
    }

    public function categories(){
        return $this->belongsToMany(
            Category::class, 
            'blog_posts_categories_pivot',
            'blog_posts_id',
            'category_id'
        )->orderBy('title', 'asc');
    }
}

