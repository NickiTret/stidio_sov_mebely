<?php

namespace App\Http\Requests\Page;

use App\Http\Requests\AdminFormRequest;

class UpdateRequest extends AdminFormRequest
{
    /** Лимит текста страницы, символов (колонка longText; лимит — защита от случайной вставки мусора). */
    public const CONTENT_MAX = 200000;

    protected array $labels = [
        'title' => 'Заголовок',
        'content' => 'Текст',
    ];

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string', 'max:' . self::CONTENT_MAX],
        ];
    }
}
