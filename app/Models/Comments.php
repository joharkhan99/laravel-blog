<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;

    protected $table = 'comments';

    //restricts columns from modifying
    protected $guarded = [];

    // user who has commented
    public function author()
    {
        // from_user is foreign key in comments table for users
        return $this->belongsTo('App\Models\User', 'from_user');
    }

    // returns post of any comment
    public function post()
    {
        // on_post is foreign key in comments table for posts
        return $this->belongsTo('App\Models\Posts', 'on_post');
    }
}
