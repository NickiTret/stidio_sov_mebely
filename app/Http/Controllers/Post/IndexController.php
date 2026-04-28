<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $sort = $request->input('sort', 'id');
        $direction = $request->input('direction', 'desc');
        $perPage = (int) $request->input('per_page', 15);

        $allowedSorts = ['id', 'title', 'created_at'];
        $allowedDirections = ['asc', 'desc'];
        $allowedPerPage = [10, 15, 25, 50];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'id';
        }

        if (!in_array($direction, $allowedDirections, true)) {
            $direction = 'desc';
        }

        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 15;
        }

        $query = Post::query()
            ->when($search !== '', function ($builder) use ($search) {
                $builder->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });

        $posts = $query
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        $stats = [
            'total' => Post::count(),
            'with_image' => Post::whereNotNull('image_src')->where('image_src', '!=', '')->count(),
            'with_description' => Post::whereNotNull('description')->where('description', '!=', '')->count(),
        ];

        return view('post.index', compact(
            'posts',
            'search',
            'sort',
            'direction',
            'perPage',
            'stats'
        ));
    }
}
