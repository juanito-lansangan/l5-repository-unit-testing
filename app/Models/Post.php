<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_title', 'post_body', 'post_author',
    ];

    public function author()
    {
        return $this->belongsTo('App\Models\User', 'post_author');
    }

    public function tags()
    {
        return $this->hasMany('App\Models\PostTag', 'post_id');
    }
}
