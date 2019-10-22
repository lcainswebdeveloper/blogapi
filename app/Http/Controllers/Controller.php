<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $model;
    protected $validator;

    /**
     * Display resource listings
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return (new $this->model)->listings();
    }

    /**
     * Display the specified resource.
     * Param can be slug or id
     * @param $param
     * @return \Illuminate\Http\Response
     */
    public function show($param)
    {
        return (new $this->model)->post($param);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validator = new $this->validator;
        $this->model = new $this->model;
        $this->validator->modelInstance = $this->model;
        $this->validator->validate();
        return $this->validator->create();
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update($id)
    {
        $this->validator = new $this->validator;
        $this->validator->modelInstance = (new $this->model)->find($id);
        $this->validator->validate();
        return $this->validator->update();
    }

    /*Use to remove any relationship records etc.*/
    protected function beforeDeleteActions($post){
        return $post;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $post = (new $this->model)->whereUserId(auth()->id())->find($id);
        if(!$post):
            return response()->json("Unable to delete this record!", 404);
        endif;

        $this->beforeDeleteActions($post);

        if($post->delete()):
            return response()->json("Record deleted successfully", 200);
        endif;
    }
}
