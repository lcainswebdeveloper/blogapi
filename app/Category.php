<?php

namespace App;

class Category extends BlogModel
{
    protected $orderBy = 'title';
    protected $orderDir = 'asc';

    public function loadRelationships($query){
        return $query->with('user');
    }

}

