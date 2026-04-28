<?php

namespace App\Http\Controllers\Preview;

use App\Http\Controllers\Controller;
use App\Support\HomePreviewContent;

class HomePreviewEditorController extends Controller
{
    public function __invoke()
    {
        $content = HomePreviewContent::load();
        $categories = HomePreviewContent::availableCategories();

        return view('preview.editor', compact('content', 'categories'));
    }
}
