<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainSet extends Model
{
    use HasFactory, FlushesSiteCache;
    protected $table = "main_sets";
    protected $primaryKey = 'id';
    protected $fillable = ['tel', 'email', 'telegram', 'watsap', 'map', 'footer', 'contact_form_enabled'];

    protected $casts = [
        'contact_form_enabled' => 'boolean',
    ];

    /**
     * Показывать ли формы заявки (блок внизу страниц, модалку и кнопки, которые её открывают).
     * Если записи настроек ещё нет или колонка пустая — форма включена.
     */
    public function isContactFormEnabled(): bool
    {
        return $this->contact_form_enabled ?? true;
    }
    protected $guarded = [];

    /**
     * Номер для ссылки tel: — только «+» и цифры. 8XXXXXXXXXX и 10-значный номер
     * приводятся к +7. Пустая строка, если телефон не задан.
     */
    public function getTelHrefAttribute(): string
    {
        $digits = preg_replace('/\D+/', '', (string) $this->tel);

        if ($digits === '') {
            return '';
        }

        if (strlen($digits) === 11 && $digits[0] === '8') {
            $digits = '7' . substr($digits, 1);
        } elseif (strlen($digits) === 10) {
            $digits = '7' . $digits;
        }

        return 'tel:+' . $digits;
    }
}
