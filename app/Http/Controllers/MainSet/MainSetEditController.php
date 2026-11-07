<?php

namespace App\Http\Controllers\MainSet;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainSet\UpdateRequest;
use App\Models\MainSet;

class MainSetEditController extends Controller
{
    public function __invoke(UpdateRequest $request)
    {
        // Настройки сайта — одна запись; создаём её при первом сохранении.
        $mainset = MainSet::first() ?? new MainSet();
        $mainset->fill($request->validated())->save();

        return redirect()
            ->route('mainset.index')
            ->with('success', 'Настройки сайта сохранены.');
    }
}
