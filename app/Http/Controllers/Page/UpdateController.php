<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Http\Requests\Page\UpdateRequest;
use App\Models\Page;

class UpdateController extends Controller
{
    /** $pageSlug ограничен ключами Page::DEFAULTS в routes/web.php. */
    public function __invoke(UpdateRequest $request, string $pageSlug)
    {
        // Одна запись на slug; создаётся при первом сохранении.
        $page = Page::findOrNewBySlug($pageSlug);
        $page->fill($request->validated())->save();

        return redirect()
            ->route('admin.page.edit', $pageSlug)
            ->with('success', $page->hasContent()
                ? 'Сохранено и опубликовано на сайте.'
                : "Сохранено. Текст пустой — страница /{$pageSlug} и ссылки на неё на сайте скрыты.");
    }
}
