@include('ssm.environment.head', ['setting' => $seting, 'mainset' => $mainset ?? null])


<body class="page__body">
    <!--LiveInternet counter-->
    {{-- <script>
        (function(d, s) {
            d.getElementById("licnt1D19").src =
                "https://counter.yadro.ru/hit?t44.6;r" + escape(d.referrer) +
                ((typeof(s) == "undefined") ? "" : ";s" + s.width + "*" + s.height + "*" +
                    (s.colorDepth ? s.colorDepth : s.pixelDepth)) + ";u" + escape(d.URL) +
                ";h" + escape(d.title.substring(0, 150)) + ";" + Math.random()
        })
        (document, screen)
    </script> --}}
    <!--/LiveInternet-->

    <div class="site-container">
        @include('ssm.environment.header', ['mainset' => $mainset])
        @include('ssm.components.new-home', ['isPreview' => false])

        @include('ssm.components.form')
    </div>
</body>

</html>
