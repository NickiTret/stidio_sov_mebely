<?php

namespace App\Http\Controllers\Seting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Seting;

class EditController extends Controller
{
    public function __invoke(Seting $seting) {
        return view('seting.edit', compact('seting'));
    }
}
