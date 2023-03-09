<?php

namespace App\Http\Controllers\Seting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Seting;

class DeleteController extends Controller
{
    public function __invoke(Seting $seting) {

        $seting->delete();

        return redirect()->route('seting.index');
    }
}
