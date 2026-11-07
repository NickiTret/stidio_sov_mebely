<?php

namespace App\Models;

use App\Models\Concerns\HasPublicImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory, HasPublicImage;

    protected static string $imageColumn = 'image_src';

    protected $table = "posts";
    protected $primaryKey = 'id';
    protected $fillable = ['title', 'description', 'image_src'];
    protected $guarded = [];
}
