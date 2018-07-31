<?php

namespace App;

class Category extends BlogModel
{
    protected $orderBy = 'title';
    protected $orderDir = 'asc';

    public function loadRelationships($query){
        return $query->with(['user', 'posts']);
    }

    public function posts()
    {
        return $this->belongsToMany(
            Category::class,
            'blog_posts_categories_pivot',
            'blog_posts_id',
            'category_id'
        );
    }
}

