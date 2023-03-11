<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use illuminate\Http\Request;

class Message extends Model
{
    use HasFactory;

    protected $table = "messages";
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'tel', 'mail', 'content'];
    protected $guarded = [];

}
