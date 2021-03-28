<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    use HasFactory;

    protected $table = 'posts';

    //restricts columns from modifying
    protected $guarded = [];

    // posts has many comments
    // returns all comments on that post
    public function comments()
    {
        return $this->hasMany('App\Models\Comments', 'on_post');
    }

    // returns the instance of the user who is author of that post
    public function author()
    {
        // author_id is foreign key in posts table for users
        return $this->belongsTo('App\Models\User', 'author_id');
    }
}
