<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $sort = $request->input('sort', 'id');
        $direction = $request->input('direction', 'desc');
        $perPage = (int) $request->input('per_page', 15);

        $allowedSorts = ['id', 'title', 'slides_count'];
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

        $query = Group::query()
            ->with(['slides:id,title,group_id'])
            ->withCount('slides')
            ->when($search !== '', function ($builder) use ($search) {
                $builder->where('title', 'like', '%' . $search . '%')
                    ->orWhereHas('slides', function ($slidesQuery) use ($search) {
                        $slidesQuery->where('title', 'like', '%' . $search . '%');
                    });
            });

        $groups = $query
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        $stats = [
            'total' => Group::count(),
            'with_slides' => Group::has('slides')->count(),
            'slides_total' => \App\Models\Slider::count(),
        ];

        return view('group.index', compact(
            'groups',
            'search',
            'sort',
            'direction',
            'perPage',
            'stats'
        ));
    }
}
