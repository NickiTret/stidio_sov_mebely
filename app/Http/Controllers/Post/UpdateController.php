<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\UpdateRequest;
use App\Models\Post;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Post $post) {

        $data = $request->validated();

        $data['image_src'] = Post::uploadImage($request);

        $post->update($data);

        $posts = Post::all();

        return view('slider.index', compact('post', 'posts'));
    }
}
