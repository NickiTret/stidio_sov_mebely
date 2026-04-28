@extends('layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Редактировать preview новой главной</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Главная</a></li>
                        <li class="breadcrumb-item active">Preview главной</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('admin.home-preview.update') }}" method="post">
                @csrf
                @method('PATCH')

                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Первый экран</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Надзаголовок</label>
                                    <input type="text" class="form-control" name="hero_eyebrow"
                                        value="{{ old('hero_eyebrow', $content['hero']['eyebrow']) }}">
                                </div>

                                <div class="form-group">
                                    <label>Главный заголовок</label>
                                    <textarea class="form-control" rows="3" name="hero_title">{{ old('hero_title', $content['hero']['title']) }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Лид-текст</label>
                                    <textarea class="form-control" rows="4" name="hero_lead">{{ old('hero_lead', $content['hero']['lead']) }}</textarea>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Текст основной кнопки</label>
                                        <input type="text" class="form-control" name="hero_primary_button_text"
                                            value="{{ old('hero_primary_button_text', $content['hero']['primary_button_text']) }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Текст второй кнопки</label>
                                        <input type="text" class="form-control" name="hero_secondary_button_text"
                                            value="{{ old('hero_secondary_button_text', $content['hero']['secondary_button_text']) }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Заголовок блока категорий</label>
                                    <input type="text" class="form-control" name="hero_quick_title"
                                        value="{{ old('hero_quick_title', $content['hero']['quick_title']) }}">
                                </div>

                                <div class="form-group">
                                    <label>Описание блока категорий</label>
                                    <textarea class="form-control" rows="3" name="hero_quick_description">{{ old('hero_quick_description', $content['hero']['quick_description']) }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Категории в первом экране</label>
                                    <select class="form-control tags" name="hero_quick_category_slugs[]" multiple>
                                        @foreach ($categories as $slug => $label)
                                            <option value="{{ $slug }}"
                                                @selected(in_array($slug, old('hero_quick_category_slugs', $content['hero']['quick_category_slugs']), true))>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Рекомендуется выбрать 3-4 категории.</small>
                                </div>

                                <div class="form-group">
                                    <label>Заголовок правого блока</label>
                                    <input type="text" class="form-control" name="hero_card_title"
                                        value="{{ old('hero_card_title', $content['hero']['card_title']) }}">
                                </div>

                                <div class="form-group mb-0">
                                    <label>Пункты правого блока</label>
                                    <textarea class="form-control" rows="5" name="hero_card_points">{{ old('hero_card_points', implode(PHP_EOL, $content['hero']['card_points'])) }}</textarea>
                                    <small class="form-text text-muted">Каждый пункт с новой строки.</small>
                                </div>
                            </div>
                        </div>

                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">FAQ, CTA и footer</h3>
                            </div>
                            <div class="card-body">
                                <h4 class="mb-3">FAQ блок</h4>
                                <div class="form-group">
                                    <label>Надзаголовок FAQ</label>
                                    <input type="text" class="form-control" name="faq_eyebrow"
                                        value="{{ old('faq_eyebrow', $content['faq']['eyebrow'] ?? '') }}">
                                </div>
                                <div class="form-group">
                                    <label>Заголовок FAQ</label>
                                    <textarea class="form-control" rows="2" name="faq_title">{{ old('faq_title', $content['faq']['title'] ?? '') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Описание FAQ</label>
                                    <textarea class="form-control" rows="3" name="faq_description">{{ old('faq_description', $content['faq']['description'] ?? '') }}</textarea>
                                </div>

                                @foreach (($content['faq']['items'] ?? []) as $index => $item)
                                    <div class="border rounded p-3 mb-3">
                                        <div class="form-group">
                                            <label>Вопрос {{ $index + 1 }}</label>
                                            <input type="text" class="form-control" name="faq_questions[]"
                                                value="{{ old("faq_questions.$index", $item['question']) }}">
                                        </div>
                                        <div class="form-group mb-0">
                                            <label>Ответ {{ $index + 1 }}</label>
                                            <textarea class="form-control" rows="3" name="faq_answers[]">{{ old("faq_answers.$index", $item['answer']) }}</textarea>
                                        </div>
                                    </div>
                                @endforeach

                                <h4 class="mb-3 mt-4">CTA блок</h4>
                                <div class="form-group">
                                    <label>Надзаголовок CTA</label>
                                    <input type="text" class="form-control" name="cta_eyebrow"
                                        value="{{ old('cta_eyebrow', $content['cta']['eyebrow'] ?? '') }}">
                                </div>
                                <div class="form-group">
                                    <label>Заголовок CTA</label>
                                    <textarea class="form-control" rows="2" name="cta_title">{{ old('cta_title', $content['cta']['title'] ?? '') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Описание CTA</label>
                                    <textarea class="form-control" rows="3" name="cta_description">{{ old('cta_description', $content['cta']['description'] ?? '') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Преимущества CTA</label>
                                    <textarea class="form-control" rows="4" name="cta_benefits">{{ old('cta_benefits', implode(PHP_EOL, $content['cta']['benefits'] ?? [])) }}</textarea>
                                    <small class="form-text text-muted">Каждый пункт с новой строки.</small>
                                </div>
                                <div class="form-group">
                                    <label>Текст кнопки CTA</label>
                                    <input type="text" class="form-control" name="cta_submit_text"
                                        value="{{ old('cta_submit_text', $content['cta']['submit_text'] ?? '') }}">
                                </div>
                                <div class="form-group">
                                    <label>Примечание под формой</label>
                                    <input type="text" class="form-control" name="cta_note"
                                        value="{{ old('cta_note', $content['cta']['note'] ?? '') }}">
                                </div>

                                <h4 class="mb-3 mt-4">Footer preview</h4>
                                <div class="form-group">
                                    <label>Надзаголовок footer</label>
                                    <input type="text" class="form-control" name="footer_eyebrow"
                                        value="{{ old('footer_eyebrow', $content['footer']['eyebrow'] ?? '') }}">
                                </div>
                                <div class="form-group">
                                    <label>Заголовок footer</label>
                                    <textarea class="form-control" rows="2" name="footer_title">{{ old('footer_title', $content['footer']['title'] ?? '') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Описание footer</label>
                                    <textarea class="form-control" rows="3" name="footer_description">{{ old('footer_description', $content['footer']['description'] ?? '') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Город / регион</label>
                                    <input type="text" class="form-control" name="footer_city"
                                        value="{{ old('footer_city', $content['footer']['city'] ?? '') }}">
                                </div>
                                <div class="form-group mb-0">
                                    <label>Текст нижней ссылки</label>
                                    <input type="text" class="form-control" name="footer_bottom_text"
                                        value="{{ old('footer_bottom_text', $content['footer']['bottom_text'] ?? '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Блоки ниже hero</h3>
                            </div>
                            <div class="card-body">
                                <h4 class="mb-3">Метрики</h4>
                                @foreach (($content['metrics']['items'] ?? []) as $index => $item)
                                    <div class="border rounded p-3 mb-3">
                                        <div class="form-group">
                                            <label>Значение {{ $index + 1 }}</label>
                                            <input type="text" class="form-control" name="metric_values[]"
                                                value="{{ old("metric_values.$index", $item['value']) }}">
                                        </div>
                                        <div class="form-group mb-0">
                                            <label>Описание {{ $index + 1 }}</label>
                                            <input type="text" class="form-control" name="metric_texts[]"
                                                value="{{ old("metric_texts.$index", $item['text']) }}">
                                        </div>
                                    </div>
                                @endforeach

                                <h4 class="mb-3 mt-4">Секция категорий</h4>
                                <div class="form-group">
                                    <label>Надзаголовок</label>
                                    <input type="text" class="form-control" name="categories_eyebrow"
                                        value="{{ old('categories_eyebrow', $content['categories_section']['eyebrow'] ?? '') }}">
                                </div>
                                <div class="form-group">
                                    <label>Заголовок</label>
                                    <textarea class="form-control" rows="2" name="categories_title">{{ old('categories_title', $content['categories_section']['title'] ?? '') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Описание</label>
                                    <textarea class="form-control" rows="3" name="categories_description">{{ old('categories_description', $content['categories_section']['description'] ?? '') }}</textarea>
                                </div>

                                <h4 class="mb-3 mt-4">Секция подхода</h4>
                                <div class="form-group">
                                    <label>Надзаголовок</label>
                                    <input type="text" class="form-control" name="approach_eyebrow"
                                        value="{{ old('approach_eyebrow', $content['approach']['eyebrow'] ?? '') }}">
                                </div>
                                <div class="form-group">
                                    <label>Заголовок</label>
                                    <textarea class="form-control" rows="2" name="approach_title">{{ old('approach_title', $content['approach']['title'] ?? '') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Описание</label>
                                    <textarea class="form-control" rows="3" name="approach_description">{{ old('approach_description', $content['approach']['description'] ?? '') }}</textarea>
                                </div>

                                @foreach (($content['approach']['steps'] ?? []) as $index => $step)
                                    <div class="border rounded p-3 mb-3">
                                        <div class="form-group">
                                            <label>Заголовок шага {{ $index + 1 }}</label>
                                            <input type="text" class="form-control" name="approach_step_titles[]"
                                                value="{{ old("approach_step_titles.$index", $step['title']) }}">
                                        </div>
                                        <div class="form-group mb-0">
                                            <label>Описание шага {{ $index + 1 }}</label>
                                            <textarea class="form-control" rows="3" name="approach_step_texts[]">{{ old("approach_step_texts.$index", $step['text']) }}</textarea>
                                        </div>
                                    </div>
                                @endforeach

                                <h4 class="mb-3 mt-4">Секция подборки проектов</h4>
                                <div class="form-group">
                                    <label>Надзаголовок</label>
                                    <input type="text" class="form-control" name="showcase_eyebrow"
                                        value="{{ old('showcase_eyebrow', $content['showcase']['eyebrow'] ?? '') }}">
                                </div>
                                <div class="form-group">
                                    <label>Заголовок</label>
                                    <textarea class="form-control" rows="2" name="showcase_title">{{ old('showcase_title', $content['showcase']['title'] ?? '') }}</textarea>
                                </div>
                                <div class="form-group mb-0">
                                    <label>Описание</label>
                                    <textarea class="form-control" rows="3" name="showcase_description">{{ old('showcase_description', $content['showcase']['description'] ?? '') }}</textarea>
                                </div>
                                <div class="form-group mt-3 mb-0">
                                    <label>Категории в подборке проектов</label>
                                    <select class="form-control tags" name="showcase_category_slugs[]" multiple>
                                        @foreach ($categories as $slug => $label)
                                            <option value="{{ $slug }}"
                                                @selected(in_array($slug, old('showcase_category_slugs', $content['showcase']['category_slugs'] ?? []), true))>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Рекомендуется выбрать 3-4 категории для блока проектов.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">Сохранить весь preview</button>
                                <a href="{{ route('admin.home-preview') }}" class="btn btn-outline-secondary">Открыть preview</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
