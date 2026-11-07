<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\Group;

class DeleteController extends Controller
{
    public function __invoke(Group $group)
    {
        // sliders.group_id ссылается на groups без ON DELETE — удаление непустой
        // группы упало бы с ошибкой БД. Сначала нужно перенести или удалить фото.
        $slidesCount = $group->slides()->count();

        if ($slidesCount > 0) {
            return redirect()
                ->route('group.index', request()->query())
                ->with('error', "Нельзя удалить группу «{$group->title}»: в ней {$slidesCount} фото. "
                    . 'Сначала перенесите их в другую группу или удалите.');
        }

        $group->delete();

        return redirect()
            ->route('group.index', request()->query())
            ->with('success', 'Группа удалена.');
    }
}
