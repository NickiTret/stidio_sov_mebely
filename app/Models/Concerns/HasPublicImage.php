<?php

namespace App\Models\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Картинка модели, лежащая на диске `public` (storage/app/public → /storage).
 *
 * Модель задаёт колонку с путём через `protected static string $imageColumn`.
 * Старый файл удаляется только после успешного сохранения записи с новым путём,
 * а при удалении записи — вместе с ней.
 */
trait HasPublicImage
{
    protected static string $imageDisk = 'public';
    protected static string $imageDirectory = 'images';

    public static function bootHasPublicImage(): void
    {
        static::updated(function (self $model) {
            $column = static::$imageColumn;

            if ($model->wasChanged($column)) {
                static::deleteImageFile($model->getOriginal($column));
            }
        });

        static::deleted(function (self $model) {
            static::deleteImageFile($model->getAttribute(static::$imageColumn));
        });
    }

    /**
     * Сохраняет загруженный файл и возвращает новый путь.
     * Если файла в запросе нет — возвращает $currentPath без изменений.
     */
    public static function uploadImage(Request $request, ?string $currentPath = null): ?string
    {
        $field = static::$imageColumn;

        if (!$request->hasFile($field)) {
            return $currentPath;
        }

        return $request->file($field)->store(
            static::$imageDirectory . '/' . date('Y-m-d'),
            static::$imageDisk
        );
    }

    public static function deleteImageFile(?string $path): void
    {
        // Удаляем только загруженные через админку файлы, ничего за пределами images/.
        if (!$path || !str_starts_with($path, static::$imageDirectory . '/') || str_contains($path, '..')) {
            return;
        }

        Storage::disk(static::$imageDisk)->delete($path);
    }

    public function getImage(): string
    {
        $path = $this->getAttribute(static::$imageColumn);

        if (!$path) {
            return asset('assets/admin/image/no_photo.png');
        }

        return asset('storage/' . $path);
    }
}
