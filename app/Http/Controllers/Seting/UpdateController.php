<?php

namespace App\Http\Controllers\Seting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seting\UpdateRequest;
use App\Models\Seting;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Seting $seting) {

        $data = $request->validated();

        $data['favicon'] = Seting::uploadImage($request);

        $seting->update($data);

        $setings = Seting::all();

        return view('seting.index', compact('seting', 'setings'));
    }
}
