<?php

namespace App\Http\Controllers\MainSet;

use App\Models\MainSet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MainSetController extends Controller
{
    public function __invoke()
    {

        $mainset = MainSet::where('id', 1)->first();

        return view('main.mainset', compact('mainset'));
    }
}
