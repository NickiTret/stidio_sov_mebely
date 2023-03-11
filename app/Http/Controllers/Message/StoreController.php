<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use App\Http\Requests\Message\StoreRequest;
use App\Models\Message;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request) {

        $data = $request->validated();
        $message = Message::create($data);

        dd($request);

        // session()->flash('success', 'Сообщение отправлено!');

        return redirect()->route('home');

    }
}
