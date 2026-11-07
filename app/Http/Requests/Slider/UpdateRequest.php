<?php

namespace App\Http\Requests\Slider;

use App\Http\Requests\AdminFormRequest;

class UpdateRequest extends AdminFormRequest
{
    protected array $labels = [
        'title' => 'Название',
        'description' => 'Описание',
        'image_src' => 'Изображение',
        'group_id' => 'Группа',
    ];

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'image_src' => static::imageRules(false),
            'group_id' => ['nullable', 'exists:groups,id'],
        ];
    }
}
