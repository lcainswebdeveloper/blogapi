<?php

namespace App\Http\Requests\Category;
use App\Http\Requests\BlogRequest;

class Request extends BlogRequest{
    protected function globalAssignFormData()
    {
        $this->modelInstance->title = $title = $this->form['title'];
        $this->modelInstance->slug = slugify($title);
        if(!isset($this->modelInstance->id)):
            $this->modelInstance->user_id = auth()->id();
        endif;
        return $this->modelInstance;
    }
}