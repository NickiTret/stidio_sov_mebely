@include('ssm.environment.head', ['setting' => $seting ])


<body class="page__body">
    <div class="site-container">
        @include('ssm.environment.header', ['mainset' => $mainset])

        <main class="main">
            @include('ssm.components.hero')
            @include('ssm.components.individual')
            @include('ssm.components.design',  ['sliders' => $sliders])
            @include('ssm.components.realish')
            @include('ssm.components.posts',  ['posts' => $posts])
        </main>

        @include('ssm.environment.footer', ['mainset' => $mainset])

        @include('ssm.components.form')
    </div>
</body>

</html>
