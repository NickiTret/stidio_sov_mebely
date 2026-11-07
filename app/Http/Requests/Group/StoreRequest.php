<?php

namespace App\Http\Requests\Group;

use App\Http\Requests\AdminFormRequest;

class StoreRequest extends AdminFormRequest
{
    protected array $labels = [
        'title' => 'Название',
    ];

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
        ];
    }
}
