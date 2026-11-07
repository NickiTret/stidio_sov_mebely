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
    protected $fillable = ['tel', 'email', 'telegram', 'watsap', 'map', 'footer'];
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
