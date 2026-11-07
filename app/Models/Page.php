<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use Illuminate\Database\Eloquent\Model;

/**
 * Текстовая страница сайта (сейчас одна — политика конфиденциальности).
 * Текст хранится как обычный текст, вставленный в админке; HTML не принимается.
 */
class Page extends Model
{
    use FlushesSiteCache;

    public const PRIVACY = 'privacy';

    /** Известные страницы: slug => заголовок по умолчанию. */
    public const DEFAULTS = [
        self::PRIVACY => 'Политика обработки персональных данных',
    ];

    protected $fillable = ['slug', 'title', 'content'];

    /** Запись страницы или новая (несохранённая) с заголовком по умолчанию. */
    public static function findOrNewBySlug(string $slug): self
    {
        return static::firstWhere('slug', $slug)
            ?? new static(['slug' => $slug, 'title' => self::DEFAULTS[$slug] ?? '']);
    }

    public function hasContent(): bool
    {
        return trim((string) $this->content) !== '';
    }

    /**
     * Текст, разбитый на блоки для вывода: каждая непустая строка — абзац.
     * Короткая строка без точки/запятой/двоеточия в конце считается заголовком
     * раздела («1. Общие положения»). Так нормально выглядит текст, вставленный
     * и из Word, и из обычного документа с пустыми строками между абзацами.
     *
     * @return array<int, array{type: 'heading'|'paragraph', text: string}>
     */
    public function blocks(): array
    {
        $lines = preg_split('/\R/u', (string) $this->content);
        $blocks = [];

        foreach ($lines as $line) {
            $line = trim(preg_replace('/[ \t\x{00A0}]+/u', ' ', $line));

            if ($line === '') {
                continue;
            }

            $isHeading = mb_strlen($line) <= 120 && !preg_match('/[.,;:]$/u', $line);
            $blocks[] = ['type' => $isHeading ? 'heading' : 'paragraph', 'text' => $line];
        }

        return $blocks;
    }
}
