<?php

namespace App\Http\Controllers\Preview;

use App\Http\Controllers\Controller;
use App\Support\HomePreviewContent;
use Illuminate\Http\Request;

class HomePreviewEditorUpdateController extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'hero_eyebrow' => ['required', 'string', 'max:255'],
            'hero_title' => ['required', 'string', 'max:500'],
            'hero_lead' => ['required', 'string', 'max:2000'],
            'hero_primary_button_text' => ['required', 'string', 'max:100'],
            'hero_secondary_button_text' => ['required', 'string', 'max:100'],
            'hero_quick_title' => ['required', 'string', 'max:255'],
            'hero_quick_description' => ['required', 'string', 'max:1000'],
            'hero_quick_category_slugs' => ['required', 'array', 'min:1', 'max:4'],
            'hero_quick_category_slugs.*' => ['required', 'string'],
            'hero_card_title' => ['required', 'string', 'max:255'],
            'hero_card_points' => ['required', 'string'],
            'faq_eyebrow' => ['required', 'string', 'max:255'],
            'faq_title' => ['required', 'string', 'max:500'],
            'faq_description' => ['required', 'string', 'max:2000'],
            'faq_questions' => ['required', 'array', 'min:1'],
            'faq_questions.*' => ['required', 'string'],
            'faq_answers' => ['required', 'array', 'min:1'],
            'faq_answers.*' => ['required', 'string'],
            'cta_eyebrow' => ['required', 'string', 'max:255'],
            'cta_title' => ['required', 'string', 'max:500'],
            'cta_description' => ['required', 'string', 'max:2000'],
            'cta_benefits' => ['required', 'string'],
            'cta_submit_text' => ['required', 'string', 'max:100'],
            'cta_note' => ['required', 'string', 'max:500'],
            'footer_eyebrow' => ['required', 'string', 'max:255'],
            'footer_title' => ['required', 'string', 'max:500'],
            'footer_description' => ['required', 'string', 'max:2000'],
            'footer_city' => ['required', 'string', 'max:255'],
            'footer_bottom_text' => ['required', 'string', 'max:255'],
            'metric_values' => ['required', 'array', 'size:3'],
            'metric_values.*' => ['required', 'string', 'max:100'],
            'metric_texts' => ['required', 'array', 'size:3'],
            'metric_texts.*' => ['required', 'string', 'max:255'],
            'categories_eyebrow' => ['required', 'string', 'max:255'],
            'categories_title' => ['required', 'string', 'max:500'],
            'categories_description' => ['required', 'string', 'max:2000'],
            'approach_eyebrow' => ['required', 'string', 'max:255'],
            'approach_title' => ['required', 'string', 'max:500'],
            'approach_description' => ['required', 'string', 'max:2000'],
            'approach_step_titles' => ['required', 'array', 'size:3'],
            'approach_step_titles.*' => ['required', 'string', 'max:255'],
            'approach_step_texts' => ['required', 'array', 'size:3'],
            'approach_step_texts.*' => ['required', 'string', 'max:1000'],
            'showcase_eyebrow' => ['required', 'string', 'max:255'],
            'showcase_title' => ['required', 'string', 'max:500'],
            'showcase_description' => ['required', 'string', 'max:2000'],
            'showcase_category_slugs' => ['required', 'array', 'min:1', 'max:4'],
            'showcase_category_slugs.*' => ['required', 'string'],
        ]);

        $payload = HomePreviewContent::load();
        $payload['hero'] = [
            'eyebrow' => $validated['hero_eyebrow'],
            'title' => $validated['hero_title'],
            'lead' => $validated['hero_lead'],
            'primary_button_text' => $validated['hero_primary_button_text'],
            'secondary_button_text' => $validated['hero_secondary_button_text'],
            'quick_title' => $validated['hero_quick_title'],
            'quick_description' => $validated['hero_quick_description'],
            'quick_category_slugs' => array_values(array_unique($validated['hero_quick_category_slugs'])),
            'card_title' => $validated['hero_card_title'],
            'card_points' => collect(preg_split('/\r\n|\r|\n/', $validated['hero_card_points']))
                ->map(fn ($item) => trim((string) $item))
                ->filter()
                ->values()
                ->all(),
        ];

        $payload['faq'] = [
            'eyebrow' => $validated['faq_eyebrow'],
            'title' => $validated['faq_title'],
            'description' => $validated['faq_description'],
            'items' => collect($validated['faq_questions'])
                ->values()
                ->map(function ($question, $index) use ($validated) {
                    return [
                        'question' => trim((string) $question),
                        'answer' => trim((string) ($validated['faq_answers'][$index] ?? '')),
                    ];
                })
                ->filter(fn (array $item) => $item['question'] !== '' && $item['answer'] !== '')
                ->values()
                ->all(),
        ];

        $payload['cta'] = [
            'eyebrow' => $validated['cta_eyebrow'],
            'title' => $validated['cta_title'],
            'description' => $validated['cta_description'],
            'benefits' => collect(preg_split('/\r\n|\r|\n/', $validated['cta_benefits']))
                ->map(fn ($item) => trim((string) $item))
                ->filter()
                ->values()
                ->all(),
            'submit_text' => $validated['cta_submit_text'],
            'note' => $validated['cta_note'],
        ];

        $payload['footer'] = [
            'eyebrow' => $validated['footer_eyebrow'],
            'title' => $validated['footer_title'],
            'description' => $validated['footer_description'],
            'city' => $validated['footer_city'],
            'bottom_text' => $validated['footer_bottom_text'],
        ];

        $payload['metrics'] = [
            'items' => collect($validated['metric_values'])
                ->values()
                ->map(function ($value, $index) use ($validated) {
                    return [
                        'value' => trim((string) $value),
                        'text' => trim((string) ($validated['metric_texts'][$index] ?? '')),
                    ];
                })
                ->values()
                ->all(),
        ];

        $payload['categories_section'] = [
            'eyebrow' => $validated['categories_eyebrow'],
            'title' => $validated['categories_title'],
            'description' => $validated['categories_description'],
        ];

        $payload['approach'] = [
            'eyebrow' => $validated['approach_eyebrow'],
            'title' => $validated['approach_title'],
            'description' => $validated['approach_description'],
            'steps' => collect($validated['approach_step_titles'])
                ->values()
                ->map(function ($title, $index) use ($validated) {
                    return [
                        'title' => trim((string) $title),
                        'text' => trim((string) ($validated['approach_step_texts'][$index] ?? '')),
                    ];
                })
                ->values()
                ->all(),
        ];

        $payload['showcase'] = [
            'eyebrow' => $validated['showcase_eyebrow'],
            'title' => $validated['showcase_title'],
            'description' => $validated['showcase_description'],
            'category_slugs' => array_values(array_unique($validated['showcase_category_slugs'])),
        ];

        HomePreviewContent::save($payload);

        return redirect()
            ->route('admin.home-preview.edit')
            ->with('success', 'Настройки preview сохранены.');
    }
}
