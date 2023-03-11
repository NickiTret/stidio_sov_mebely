<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Message;

class IndexController extends Controller
{
    public function __invoke() {
        $messages = Message::all();
        return view('message.index', compact('messages'));
    }
}
