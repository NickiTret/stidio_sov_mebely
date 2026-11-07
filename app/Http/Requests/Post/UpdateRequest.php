<?php

namespace App\Http\Requests\Post;

use App\Http\Requests\AdminFormRequest;

class UpdateRequest extends AdminFormRequest
{
    protected array $labels = [
        'title' => 'Заголовок',
        'description' => 'Текст',
        'image_src' => 'Изображение',
    ];

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:20000'],
            'image_src' => static::imageRules(false),
        ];
    }
}
