<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DeleteAllController extends Controller
{
    /** Слово, которое админ должен ввести, чтобы удалить все заявки разом. */
    public const CONFIRMATION_WORD = 'УДАЛИТЬ';

    public function __invoke(Request $request)
    {
        $typed = Str::upper(trim((string) $request->input('confirmation')));

        if ($typed !== self::CONFIRMATION_WORD) {
            return redirect()
                ->route('message.index')
                ->with('error', 'Заявки не удалены: для подтверждения нужно ввести слово «' . self::CONFIRMATION_WORD . '».');
        }

        // SoftDeletes: заявки уходят в корзину, их можно восстановить.
        $deletedCount = Message::query()->delete();

        return redirect()
            ->route('message.index')
            ->with('success', "Заявки перемещены в корзину: {$deletedCount}. Их можно восстановить.");
    }
}
