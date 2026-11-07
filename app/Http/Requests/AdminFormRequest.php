<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * База для форм админки: общие правила для картинок и русские сообщения об ошибках
 * (в проекте locale=en и нет lang/ru, поэтому тексты заданы здесь).
 */
abstract class AdminFormRequest extends FormRequest
{
    /** Максимальный размер загружаемой картинки, КБ. */
    public const IMAGE_MAX_KB = 5120;

    /** Подписи полей для сообщений: ['title' => 'Название', ...]. */
    protected array $labels = [];

    public function authorize(): bool
    {
        return true;
    }

    /** Правила для картинки: при создании обязательна, при редактировании — нет. */
    protected static function imageRules(bool $required): array
    {
        return [
            $required ? 'required' : 'nullable',
            'file',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:' . self::IMAGE_MAX_KB,
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Заполните поле «:attribute».',
            'string' => 'Поле «:attribute» должно быть текстом.',
            // Laravel ищет кастомные сообщения по ключам «rule» и «поле.rule», поэтому
            // для картинок размер задаём по именам полей, для остальных — общий текстовый.
            'max' => 'Поле «:attribute» — не больше :max символов.',
            'image_src.max' => 'Файл «:attribute» — не больше ' . (self::IMAGE_MAX_KB / 1024) . ' МБ.',
            'favicon.max' => 'Файл «:attribute» — не больше ' . (self::IMAGE_MAX_KB / 1024) . ' МБ.',
            'file' => 'Не удалось загрузить файл «:attribute».',
            'uploaded' => 'Не удалось загрузить файл «:attribute». Возможно, он слишком большой.',
            'image' => 'Файл «:attribute» должен быть картинкой.',
            'mimes' => 'Файл «:attribute»: допустимы только JPG, PNG или WebP.',
            'exists' => 'Выбранное значение поля «:attribute» не найдено.',
        ];
    }

    public function attributes(): array
    {
        return $this->labels;
    }
}
