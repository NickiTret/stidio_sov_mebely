<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Group;

class IndexController extends Controller
{
    public function __invoke() {
        $groups = Group::all();
        return view('group.index', compact('groups'));
    }
}
