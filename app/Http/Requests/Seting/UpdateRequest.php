<?php

namespace App\Http\Requests\Seting;

use App\Models\Seting;

class UpdateRequest extends StoreRequest
{
    protected bool $imageRequired = false;

    protected function currentSeting(): ?Seting
    {
        return $this->route('seting');
    }
}
