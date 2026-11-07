<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\Page;

class EditController extends Controller
{
    /** $pageSlug ограничен ключами Page::DEFAULTS в routes/web.php. */
    public function __invoke(string $pageSlug)
    {
        $page = Page::findOrNewBySlug($pageSlug);
        $adminLabel = Page::ADMIN_LABELS[$pageSlug] ?? $page->title;

        return view('page.edit', compact('page', 'adminLabel'));
    }
}
