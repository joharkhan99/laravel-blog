<?php

namespace App\Http\Controllers;

use App\Models\Comments as ModelsComments;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //At this time we are not adding any code to UserController. We will use this controller for displaying posts and drafts ofindivisible authors and also for creating profiles for users.

    public function store(Request $request)
    {
        $input['from_user'] = $request->user()->id;
        $input['on_post'] = $request->input('on_post');
        $input['body'] = $request->input('body');
        $slug = $request->input('slug');

        ModelsComments::create($input);
        return redirect($slug)->with('message', 'Comment published');
    }
}
