<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

use App\Models\Post;

class EditController extends Controller
{
    public function __invoke(Post $post) {
        $groups = Post::all();
        return view('post.edit', compact('post', 'groups'));
    }
}
