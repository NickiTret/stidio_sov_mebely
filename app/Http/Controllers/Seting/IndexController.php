<?php

namespace App\Http\Controllers\Seting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Seting;

class IndexController extends Controller
{
    public function __invoke() {
        $setings = Seting::all();
        return view('seting.index', compact('setings'));
    }
}
