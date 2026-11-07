<?php

namespace App\Http\Requests\Seting;

use App\Http\Requests\AdminFormRequest;
use App\Models\Seting;
use Illuminate\Validation\Rule;

class StoreRequest extends AdminFormRequest
{
    protected array $labels = [
        'page' => 'Страница',
        'title' => 'Title',
        'descriptions' => 'Description',
        'keywords' => 'Keywords',
        'favicon' => 'Иконка / изображение',
    ];

    /** Картинка обязательна только при создании. */
    protected bool $imageRequired = true;

    /** Запись, которую редактируем (её страницу не считаем занятой). */
    protected function currentSeting(): ?Seting
    {
        return null;
    }

    public function rules(): array
    {
        return [
            // Сайт ищет настройки строго по этим значениям, поэтому — только из списка
            // и не больше одной записи на страницу.
            'page' => [
                'required',
                'string',
                Rule::in(array_keys(Seting::PAGES)),
                Rule::unique('setings', 'page')->ignore($this->currentSeting()),
            ],
            'title' => ['required', 'string', 'max:255'],
            'descriptions' => ['required', 'string', 'max:1000'],
            'keywords' => ['required', 'string', 'max:1000'],
            'favicon' => static::imageRules($this->imageRequired),
        ];
    }

    public function messages(): array
    {
        return parent::messages() + [
            'page.in' => 'Выберите страницу из списка.',
            'page.unique' => 'Для этой страницы настройки уже есть — отредактируйте существующие.',
        ];
    }
}
