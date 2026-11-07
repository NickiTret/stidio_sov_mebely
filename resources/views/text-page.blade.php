@include('ssm.environment.head', ['setting' => $seting, 'seoData' => $seoData ?? null, 'mainset' => $mainset ?? null])

<body class="page__body">
    <div class="site-container">
        @include('ssm.environment.header', ['mainset' => $mainset])

        <main class="main service-page legal-page">
            <section class="service-hero">
                <div class="container">
                    <div class="service-hero__content">
                        <span class="service-hero__eyebrow">Документы</span>
                        <h1>{{ $page->title }}</h1>
                        @if ($page->updated_at)
                            <p>Редакция от {{ $page->updated_at->format('d.m.Y') }}</p>
                        @endif
                    </div>
                </div>
            </section>

            <section class="service-section">
                <div class="container">
                    {{-- Текст из админки — обычный текст, выводится только через экранирование {{ }}. --}}
                    <article class="service-hero__content legal-text">
                        @foreach ($page->blocks() as $block)
                            @if ($block['type'] === 'heading')
                                <h2>{{ $block['text'] }}</h2>
                            @else
                                <p>{{ $block['text'] }}</p>
                            @endif
                        @endforeach
                    </article>
                </div>
            </section>

            @include('ssm.components.site-footer-new', [
                'previewContent' => $previewContent,
                'groups' => $groups,
                'mainset' => $mainset,
                'seting' => $seting,
                'footerLinkHref' => route('home'),
                'footerLinkLabel' => 'На главную',
            ])
        </main>

        @include('ssm.components.form')
    </div>
</body>

</html>
