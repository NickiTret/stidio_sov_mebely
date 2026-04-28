<?php

namespace App\Http\Controllers\Seting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Seting\StoreRequest;

use App\Models\Seting;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        $data['favicon'] = Seting::uploadImage($request);
        Seting::create($data);

        return redirect()
            ->route('seting.index')
            ->with('success', 'Настройки страницы созданы.');
    }
}
