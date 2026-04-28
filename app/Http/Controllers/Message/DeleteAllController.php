<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use App\Models\Message;

class DeleteAllController extends Controller
{
    public function __invoke()
    {
        $deletedCount = Message::query()->delete();

        return redirect()
            ->route('message.index')
            ->with('success', 'Все заявки удалены. Удалено записей: ' . $deletedCount . '.');
    }
}
