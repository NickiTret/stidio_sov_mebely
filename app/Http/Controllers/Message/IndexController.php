<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        $perPage = (int) $request->input('per_page', 15);

        $allowedSorts = ['id', 'name', 'tel', 'mail', 'created_at'];
        $allowedDirections = ['asc', 'desc'];
        $allowedPerPage = [10, 15, 25, 50];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        if (!in_array($direction, $allowedDirections, true)) {
            $direction = 'desc';
        }

        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 15;
        }

        $query = Message::query()
            ->when($search !== '', function ($builder) use ($search) {
                $builder->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', '%' . $search . '%')
                        ->orWhere('tel', 'like', '%' . $search . '%')
                        ->orWhere('mail', 'like', '%' . $search . '%')
                        ->orWhere('content', 'like', '%' . $search . '%');
                });
            });

        $messages = $query
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        $stats = [
            'total' => Message::count(),
            'today' => Message::whereDate('created_at', now()->toDateString())->count(),
            'with_content' => Message::where('content', '!=', '')->count(),
        ];

        return view('message.index', compact(
            'messages',
            'search',
            'sort',
            'direction',
            'perPage',
            'stats'
        ));
    }
}
