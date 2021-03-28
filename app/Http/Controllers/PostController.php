<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostFormRequest;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
  //
  public function index()
  {
    // paginate method counts the total number of records matched by the query before retrieving the records from the database.
    $posts = Posts::where('active', 1)->orderBy('created_at', 'desc')->paginate(5);

    // page heading
    $title = 'Latest Posts';
    //return home.blade.php template from resources/views folder
    return view('home')->withPosts($posts)->withTitle($title);
  }

  public function create(Request $request)
  {
    if ($request->user()->can_post()) {
      return view('posts.create');
    } else {
      return redirect('/')->withErrors('You have not sufficient permissions for writing post');
    }
  }

  public function store(PostFormRequest $request)
  {
    $post = new Posts();

    $post->title = $request->get('title');
    $post->body = $request->get('body');
    $post->slug = Str::slug($post->title);

    $duplicate = Posts::where('slug', $post->slug)->first();
    if ($duplicate) {
      return redirect('new-post')->withErrors('Title already exists.')->withInput();
    }

    $post->author_id = $request->user()->id;
    if ($request->has('save')) {
      $post->active = 0;
      $message = 'Post saved successfully';
    } else {
      $post->active = 1;
      $message = 'Post published successfully';
    }

    $post->save();
    return redirect('edit/' . $post->slug)->withMessage($message);
  }

  /* This function is taking slug as argument and in line 3 the post is fetched from the database. If a post exits then we are fetching comments. This relation between Post model and comments is defined in comments() function in Post model.*/
  public function show($slug)
  {
    $post = Posts::where('slug', $slug)->first();
    if (!$post) {
      return redirect('/')->withErrors('requested page not found');
    }

    $comments = $post->comments;    //get post comments if exists
    return view('posts.show')->withPost($post)->withComments($comments);
  }

  /* In line 3 we are fetching the post from the database and in line 4 we are checking that the user who is requesting is a valid user (admin or author of that post) or not. */
  public function edit(Request $request)
  {
    $post = Posts::where('slug', $request->slug)->first();
    if ($post && ($request->user()->id == $post->author_id || $request->user()->is_admin())) {
      return view('posts.view')->with('post', $post);
    }

    return redirect('/')->withErrors('you have not sufficient permissions');
  }

  public function update(Request $request)
  {
    $post_id = $request->input('post_id');
    $post = Posts::find($post_id);

    if ($post && ($post->author_id == $request->user()->id || $request->user()->is_admin())) {
      $title = $request->input('title');
      $slug = Str::slug($title);
      $duplicate = Posts::where('slug', $slug)->first();
      if ($duplicate) {
        if ($duplicate->id != $post_id) {
          return redirect('edit/' . $post->slug)->withErrors('Title already exists.')->withInput();
        } else {
          $post->slug = $slug;
        }
      }

      $post->title = $title;
      $post->body = $request->input('body');

      if ($request->has('save')) {
        $post->active = 0;
        $message = 'Post saved successfully';
        $landing = 'edit/' . $post->slug;
      } else {
        $post->active = 1;
        $message = 'Post updated successfully';
        $landing = $post->slug;
      }

      $post->save();
      return redirect($landing)->withMessage($message);
    } else {
      return redirect('/')->withErrors('you have not sufficient permissions');
    }
  }

  public function delete(Request $request, $id)
  {
    $post = Posts::find($id);

    if ($post && ($post->suthor_id == $request->user()->id || $request->user()->is_admin())) {
      $post->delete();
      $date['message'] = 'Post deleted Successfully';
    } else {
      $data['errors'] = 'Invalid Operation. You have not sufficient permissions';
    }

    return redirect('/')->with($data);
  }
}
