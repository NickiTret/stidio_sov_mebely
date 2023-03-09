<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Post\StoreRequest;
use App\Models\Group;
use App\Models\Post;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request) {

        $data = $request->validated();
        $data['image_src'] = Post::uploadImage($request);
        $post = Post::create($data);

        return redirect()->route('post.index');

    }
}
