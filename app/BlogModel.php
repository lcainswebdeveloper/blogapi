<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogModel extends Model
{
    protected $orderBy = 'id';
    protected $orderDir = 'desc';

    public function scopeListings($query)
    {
        return $this->decorated($query)->orderBy($this->orderBy, $this->orderDir)->get();
    }

    protected function loadRelationships($query)
    {
        return $query;
    }

    protected function decorated($query)
    {
        $this->loadRelationships($query);
        return $query;
    }

    public function scopePost($query, $param)
    {
        if (is_numeric($param)):
            $post = $this->decorated($query)->find($param); else:
            $post = $this->decorated($query)->whereSlug($param)->first();
        endif;
        if (!$post):
            return response()->json("Record not found", 404);
        endif;
        return $post;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
