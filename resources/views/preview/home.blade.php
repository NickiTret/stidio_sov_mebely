@include('ssm.environment.head', ['setting' => $seting, 'mainset' => $mainset ?? null, 'seoData' => $seoData])

<body class="page__body page__body--preview">
    <div class="site-container">
        @include('ssm.environment.header', ['mainset' => $mainset])
        @include('ssm.components.new-home', ['isPreview' => true])
        @include('ssm.components.form')
    </div>
</body>

</html>
