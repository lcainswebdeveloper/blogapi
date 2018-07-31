<?php

namespace App;

class BlogPost extends BlogModel
{
    protected $orderBy = 'created_at';
    protected $orderDir = 'desc';

    public function loadRelationships($query){
        return $query->with('user');
    }

}

