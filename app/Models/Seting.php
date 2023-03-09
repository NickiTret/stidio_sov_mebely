<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use illuminate\Http\Request;

class Seting extends Model
{
    use HasFactory;

    protected $table = "setings";
    protected $primaryKey = 'id';
    protected $fillable = ['page', 'title', 'descriptions', 'keywords', 'favicon'];
    protected $guarded = [];

    public static function uploadImage(Request $request, $favicon = null)
    {
        if ($request->hasFile('favicon')) {

            if ($favicon)
            {
                Storage::delete($favicon);
            }
            $folder = date('Y-m-d');

            return $request->file('favicon')->store("images/{$folder}", "public");
        }

        return null;
    }

    public function getImage()
    {
        if(!$this->favicon)
        {
            return asset("assets/admin/image/no_photo.png");
        }

        return asset( 'storage/' . $this->favicon);
    }
}
