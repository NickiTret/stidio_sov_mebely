<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use App\Models\Concerns\HasPublicImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seting extends Model
{
    use HasFactory, HasPublicImage, FlushesSiteCache;

    protected static string $imageColumn = 'favicon';

    /**
     * Значения колонки `page`, которые сайт реально ищет (MainController через SiteCache),
     * и подписи для выпадающего списка в админке.
     */
    public const PAGES = [
        'Главная' => 'Главная, контакты и страницы услуг',
        'Галерея' => 'Галерея и категории галереи',
    ];

    public function isUsedOnSite(): bool
    {
        return array_key_exists((string) $this->page, self::PAGES);
    }

    protected $table = "setings";
    protected $primaryKey = 'id';
    protected $fillable = ['page', 'title', 'descriptions', 'keywords', 'favicon'];
    protected $guarded = [];
}
