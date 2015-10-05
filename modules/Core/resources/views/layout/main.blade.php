@include('core::layout.meta')

<body id="body.{{ $bodyId }}">
    @include('core::layout.header')

    <div id="contentContainer" class="container">
        @yield('content')
    </div>

    @include('core::layout.footer')
</body>
</html>