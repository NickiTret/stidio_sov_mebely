<?php

namespace App\Models\Concerns;

use App\Support\SiteCache;

/** Сбрасывает кеш публичной части сайта при любом изменении записи модели. */
trait FlushesSiteCache
{
    public static function bootFlushesSiteCache(): void
    {
        static::saved(fn () => SiteCache::flush());
        static::deleted(fn () => SiteCache::flush());
    }
}
