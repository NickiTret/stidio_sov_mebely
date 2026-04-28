<?php

namespace App\Http\Requests\Message;

use Closure;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:60'],
            'tel' => ['required', 'string', 'min:10', 'max:25'],
            'mail' => ['required', 'email:rfc', 'max:120'],
            'content' => ['nullable', 'string', 'min:5', 'max:2000'],
            'redirect_to' => ['nullable', 'string', 'max:2000'],
            'website' => [
                'nullable',
                'string',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (!empty($value)) {
                        $fail('Спам-защита сработала.');
                    }
                },
            ],
            'form_started_at' => [
                'required',
                'integer',
                function (string $attribute, mixed $value, Closure $fail) {
                    $startedAt = (int) $value;

                    if ($startedAt <= 0 || (time() - $startedAt) < 3) {
                        $fail('Форма отправлена слишком быстро. Попробуйте еще раз.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Введите имя.',
            'name.min' => 'Имя должно быть не короче 2 символов.',
            'name.max' => 'Имя не должно быть длиннее 60 символов.',
            'tel.required' => 'Введите телефон.',
            'tel.min' => 'Укажите телефон полностью.',
            'mail.required' => 'Введите email.',
            'mail.email' => 'Проверьте формат email.',
            'content.min' => 'Добавьте чуть больше деталей по проекту.',
            'content.max' => 'Сообщение получилось слишком длинным.',
        ];
    }
}
