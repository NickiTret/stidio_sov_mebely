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
            @if (!empty($selectedGroup))
                @include('ssm.components.category-benefits', ['group' => $selectedGroup, 'groups' => $groups, 'mainset' => $mainset])
            @endif
        </main>

        @include('ssm.environment.footer', ['mainset' => $mainset, 'setting' => $seting])

        @include('ssm.components.form')
    </div>
</body>

</html>
