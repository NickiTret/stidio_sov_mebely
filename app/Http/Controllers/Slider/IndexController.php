<?php

namespace App\Http\Controllers\Slider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $sort = $request->input('sort', 'id');
        $direction = $request->input('direction', 'desc');
        $perPage = (int) $request->input('per_page', 15);

        $allowedSorts = ['id', 'title', 'group_id', 'created_at'];
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

        $query = Slider::query()
            ->with('group:id,title')
            ->when($search !== '', function ($builder) use ($search) {
                $builder->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('group', function ($groupQuery) use ($search) {
                        $groupQuery->where('title', 'like', '%' . $search . '%');
                    });
            });

        $sliders = $query
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        $stats = [
            'total' => Slider::count(),
            'with_group' => Slider::whereNotNull('group_id')->count(),
            'groups_total' => \App\Models\Group::count(),
        ];

        return view('slider.index', compact(
            'sliders',
            'search',
            'sort',
            'direction',
            'perPage',
            'stats'
        ));
    }
}
