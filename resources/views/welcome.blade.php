@include('ssm.environment.head', ['setting' => $seting])


<body class="page__body">
    <!--LiveInternet counter--><a href="https://www.liveinternet.ru/click" target="_blank"><img id="licnt1D19"
            width="31" height="31" style="border:0" title="LiveInternet"
            src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAEALAAAAAABAAEAAAIBTAA7" alt="" /></a>
    <script>
        (function(d, s) {
            d.getElementById("licnt1D19").src =
                "https://counter.yadro.ru/hit?t44.6;r" + escape(d.referrer) +
                ((typeof(s) == "undefined") ? "" : ";s" + s.width + "*" + s.height + "*" +
                    (s.colorDepth ? s.colorDepth : s.pixelDepth)) + ";u" + escape(d.URL) +
                ";h" + escape(d.title.substring(0, 150)) + ";" + Math.random()
        })
        (document, screen)
    </script>
    <!--/LiveInternet-->

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
            @include('ssm.components.factoid')
            @include('ssm.components.individual')
            @include('ssm.components.realish')
            @include('ssm.components.posts', ['posts' => $posts])
        </main>

        @include('ssm.environment.footer', ['mainset' => $mainset, 'setting' => $seting])

        @include('ssm.components.form')
    </div>
</body>

</html>
