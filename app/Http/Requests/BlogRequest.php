<?php

namespace App\Http\Requests;

class BlogRequest{
    public $modelInstance;
    protected $form;

    public function __construct(){
        $this->form = request()->all();
    }

    /*Validation*/
    public function globalValidationRules()
    {
        return [
            'rules'  => [
                'title' => $this->titleValidation()
            ],
            'messages' => []
        ];
    }

    protected function titleValidation($titleCollumn = 'title'){
        $validationString = 'required|unique:'.$this->modelInstance->getTable().','.$titleCollumn;

        if(isset($this->modelInstance->id)):
            $validationString = 'required|unique:'.$this->modelInstance->getTable().','.$titleCollumn.','.$this->modelInstance->id;
        endif;

        return $validationString;
    }

    public function validate()
    {
        $inputs = request()->validate(
            $this->globalValidationRules()['rules'],
            $this->globalValidationRules()['messages']
        );
        return $inputs;
    }

    protected function globalAssignFormData()
    {
        return $this->modelInstance;
    }

    protected function savedItem(){
        return $this->modelInstance->post($this->modelInstance->id);
    }

    protected function postSave(){
        return $this->modelInstance;
    }

    protected function persist(){
        $record = $this->globalAssignFormData();
        $record->save();
        $this->modelInstance = $record;
        $this->postSave();
        return $this->savedItem();
    }

    public function create()
    {
        return $this->persist();
    }

    public function update()
    {
        return $this->persist();
    }

}
