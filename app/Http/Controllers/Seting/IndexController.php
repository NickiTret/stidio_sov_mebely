<?php

namespace App\Http\Controllers\Seting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seting;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $sort = $request->input('sort', 'id');
        $direction = $request->input('direction', 'desc');
        $perPage = (int) $request->input('per_page', 15);

        $allowedSorts = ['id', 'page', 'title'];
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

        $query = Seting::query()
            ->when($search !== '', function ($builder) use ($search) {
                $builder->where('page', 'like', '%' . $search . '%')
                    ->orWhere('title', 'like', '%' . $search . '%')
                    ->orWhere('descriptions', 'like', '%' . $search . '%')
                    ->orWhere('keywords', 'like', '%' . $search . '%');
            });

        $setings = $query
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        $stats = [
            'total' => Seting::count(),
            'with_favicon' => Seting::whereNotNull('favicon')->where('favicon', '!=', '')->count(),
            'with_keywords' => Seting::whereNotNull('keywords')->where('keywords', '!=', '')->count(),
        ];

        return view('seting.index', compact(
            'setings',
            'search',
            'sort',
            'direction',
            'perPage',
            'stats'
        ));
    }
}
