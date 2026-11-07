<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Http\Requests\Page\UpdateRequest;
use App\Models\Page;

class PrivacyUpdateController extends Controller
{
    public function __invoke(UpdateRequest $request)
    {
        // Одна запись на slug; создаётся при первом сохранении.
        $page = Page::findOrNewBySlug(Page::PRIVACY);
        $page->fill($request->validated())->save();

        return redirect()
            ->route('admin.privacy.edit')
            ->with('success', $page->hasContent()
                ? 'Политика сохранена и опубликована на сайте.'
                : 'Сохранено. Текст пустой — страница /privacy и ссылки на неё на сайте скрыты.');
    }
}
