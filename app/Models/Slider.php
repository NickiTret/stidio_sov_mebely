<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use App\Models\Concerns\HasPublicImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory, HasPublicImage, FlushesSiteCache;

    protected static string $imageColumn = 'image_src';

    protected $table = "sliders";
    protected $primaryKey = 'id';
    protected $fillable = ['title', 'description', 'image_src', 'group_id'];
    protected $guarded = [];

    //связь один ко многим с таблицей группы
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
}
