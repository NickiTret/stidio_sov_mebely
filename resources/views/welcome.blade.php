@include('ssm.environment.head', ['setting' => $seting])


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
            @include('ssm.components.hero')
            @include('ssm.components.individual')
            @include('ssm.components.design', ['sliders' => $sliders])
            @include('ssm.components.realish')
            @include('ssm.components.posts', ['posts' => $posts])
        </main>

        @include('ssm.environment.footer', ['mainset' => $mainset, 'setting' => $seting])

        @include('ssm.components.form')
    </div>
</body>

</html>
