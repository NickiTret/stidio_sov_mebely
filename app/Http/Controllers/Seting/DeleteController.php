<?php

namespace App\Http\Controllers\Seting;

use App\Http\Controllers\Controller;
use App\Models\Seting;

class DeleteController extends Controller
{
    public function __invoke(Seting $seting)
    {
        // Записи из Seting::PAGES сайт использует для title/description/иконки.
        // Без них страницы работают на запасных значениях, но SEO теряется —
        // поэтому их можно только редактировать.
        if ($seting->isUsedOnSite()) {
            return redirect()
                ->route('seting.index')
                ->with('error', "Настройки страницы «{$seting->page}» используются на сайте, их нельзя удалить — только изменить.");
        }

        $seting->delete();

        return redirect()
            ->route('seting.index')
            ->with('success', 'Настройки страницы удалены.');
    }
}
