<?php

namespace App\Support;

use App\Models\Group;
use App\Models\MainSet;
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


    public static function mainset(): ?MainSet
    {
        return Cache::remember('site:mainset', self::TTL_SECONDS, fn () => MainSet::first());
    }

    public static function seting(string $page): ?Seting
    {
        return Cache::remember(self::setingKey($page), self::TTL_SECONDS, fn () => Seting::where('page', $page)->first());
    }

    public static function groupsWithSlides(): Collection
    {
        return Cache::remember('site:groups', self::TTL_SECONDS, fn () => Group::with('slides')->get());
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
    }

    private static function setingKey(string $page): string
    {
        return 'site:seting:' . md5($page);
    }
}
