<?php

namespace App\Support;

use App\Models\Group;
use App\Models\MainSet;
use App\Models\Page;
use App\Models\Seting;
use App\Models\Slider;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Кеш данных публичной части сайта. Все ключи собраны здесь, чтобы их можно было
 * сбросить одним вызовом: модели с трейтом FlushesSiteCache вызывают flush()
 * при сохранении/удалении, поэтому правки из админки видны на сайте сразу.
 */
class SiteCache
{
    /** Страховочный срок жизни на случай правок мимо моделей (прямо в БД). */
    private const TTL_SECONDS = 600;


    /**
     * Контакты сайта. Если запись ещё не создана (свежая установка), возвращается пустая
     * несохранённая модель — шаблоны не падают, а контакты просто не выводятся.
     */
    public static function mainset(): MainSet
    {
        return Cache::remember('site:mainset', self::TTL_SECONDS, fn () => MainSet::first() ?? new MainSet());
    }

    public static function seting(string $page): ?Seting
    {
        return Cache::remember(self::setingKey($page), self::TTL_SECONDS, fn () => Seting::where('page', $page)->first());
    }

    public static function groupsWithSlides(): Collection
    {
        return Cache::remember('site:groups', self::TTL_SECONDS, fn () => Group::with('slides')->get());
    }

    /**
     * Группы, которые реально показываются на сайте: с фото и непустым slug,
     * в порядке Group::categoryDefinitions(). Единый источник для галереи, меню и sitemap —
     * поэтому там нет ссылок на категории, которые отдали бы 404.
     */
    public static function galleryGroups(): Collection
    {
        $orderedSlugs = collect(Group::categoryDefinitions())->pluck('slug')->all();

        return self::groupsWithSlides()
            ->filter(fn (Group $group) => $group->slides->isNotEmpty() && $group->slug !== '')
            ->unique(fn (Group $group) => $group->slug)
            ->sortBy(function (Group $group) use ($orderedSlugs) {
                $position = array_search($group->slug, $orderedSlugs, true);

                return $position === false ? PHP_INT_MAX : $position;
            })
            ->values();
    }

    /** Текстовая страница с непустым текстом или null (тогда страница отдаёт 404, а ссылки на неё скрыты). */
    public static function page(string $slug): ?Page
    {
        $page = Cache::remember('site:page:' . $slug, self::TTL_SECONDS, fn () => Page::firstWhere('slug', $slug) ?? false);

        return $page && $page->hasContent() ? $page : null;
    }

    public static function sliders(): Collection
    {
        return Cache::remember('site:sliders', self::TTL_SECONDS, fn () => Slider::all());
    }

    public static function flush(): void
    {
        Cache::forget('site:mainset');
        Cache::forget('site:groups');
        Cache::forget('site:sliders');

        foreach (array_keys(Seting::PAGES) as $page) {
            Cache::forget(self::setingKey($page));
        }

        foreach (array_keys(Page::DEFAULTS) as $slug) {
            Cache::forget('site:page:' . $slug);
        }
    }

    private static function setingKey(string $page): string
    {
        return 'site:seting:' . md5($page);
    }
}
