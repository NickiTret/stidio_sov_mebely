<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use App\Models\Message;

class DeleteController extends Controller
{
    public function __invoke(Message $message)
    {
        $message->delete();

        return redirect()
            ->route('message.index', request()->query())
            ->with('success', 'Заявка удалена.');
    }
}
