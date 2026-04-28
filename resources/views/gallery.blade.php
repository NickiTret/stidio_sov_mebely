@include('ssm.environment.head', ['setting' => $seting, 'seoData' => $seoData ?? null, 'mainset' => $mainset ?? null])


<body class="page__body">
    <div class="site-container">
        @include('ssm.environment.header', ['mainset' => $mainset])

        <main class="main">
            <div class='container pt-3'>
                <div class="row">
                    <div class="col-12">
                        @if (session()->has('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @include('ssm.components.design', ['sliders' => $sliders, 'groups' => $groups, 'selectedGroup' => $selectedGroup ?? null])
            @include('ssm.components.site-cta-new', [
                'previewContent' => $previewContent,
                'ctaPlaceholder' => !empty($selectedGroup)
                    ? 'Например: шпонированная кухня, шкаф-купе или гардеробная из раздела ' . $selectedGroup->display_title
                    : 'Например: шпонированная кухня, шкаф, гардеробная или кабинет',
            ])
        </main>

        @include('ssm.components.site-footer-new', [
            'previewContent' => $previewContent,
            'groups' => $groups,
            'mainset' => $mainset,
            'seting' => $seting,
            'footerLinkHref' => route('home'),
            'footerLinkLabel' => 'На главную',
        ])

        @include('ssm.components.form')
    </div>
</body>

</html>
