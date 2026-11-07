<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PrivacyEditController extends Controller
{
    public function __invoke()
    {
        $page = Page::findOrNewBySlug(Page::PRIVACY);

        return view('page.privacy', compact('page'));
    }
}
