<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainSet extends Model
{
    use HasFactory;
    protected $table = "main_sets";
    protected $primaryKey = 'id';
    protected $fillable = ['tel', 'email', 'telegram', 'watsap', 'map', 'footer'];
    protected $guarded = [];
}
