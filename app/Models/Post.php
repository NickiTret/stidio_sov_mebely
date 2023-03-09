<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use illuminate\Http\Request;

class Post extends Model
{
    use HasFactory;

    protected $table = "posts";
    protected $primaryKey = 'id';
    protected $fillable = ['title', 'descriptions', 'image_src'];
    protected $guarded = [];

    public static function uploadImage(Request $request, $image_src = null)
    {
        if ($request->hasFile('image_src')) {

            if ($image_src)
            {
                Storage::delete($image_src);
            }
            $folder = date('Y-m-d');

            return $request->file('image_src')->store("images/{$folder}", "public");
        }

        return null;
    }

    public function getImage()
    {
        if(!$this->image_src)
        {
            return asset("assets/admin/image/no_photo.png");
        }

        return asset( 'storage/' . $this->image_src);
    }
}
