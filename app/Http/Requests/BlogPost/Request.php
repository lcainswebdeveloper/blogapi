<?php

namespace App\Http\Requests\BlogPost;
use App\Http\Requests\BlogRequest;

class Request extends BlogRequest{
    public function globalValidationRules()
    {
        return [
            'rules'  => [
                'title' => $this->titleValidation(),
                'content' => 'required'
            ],
            'messages' => []
        ];
    }

    protected function globalAssignFormData()
    {
        $this->modelInstance->title = $title = $this->form['title'];
        $this->modelInstance->slug = slugify($title);
        $this->modelInstance->content = $this->form['content'];

        if(!isset($this->modelInstance->id)):
            $this->modelInstance->user_id = auth()->id();
        endif;
        
        return $this->modelInstance;
    }

    protected function postSave(){
        if(isset($this->form['categories'])):
            $this->modelInstance->categories()->sync($this->form['categories']);
        endif;

        return $this->modelInstance;
    }
}