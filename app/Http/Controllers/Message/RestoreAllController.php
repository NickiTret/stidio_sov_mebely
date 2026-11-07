<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use App\Models\Message;

class RestoreAllController extends Controller
{
    public function __invoke()
    {
        $restoredCount = Message::onlyTrashed()->restore();

        return redirect()
            ->route('message.index')
            ->with('success', "Восстановлено заявок: {$restoredCount}.");
    }
}
