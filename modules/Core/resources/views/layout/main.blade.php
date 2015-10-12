@include('core::layout.partials.meta')

<body id="body.{{ $bodyId }}">
    @include('core::layout.partials.header')

    <div id="contentContainer" class="container">
        @yield('content')
    </div>

    @include('core::layout.partials.footer')
</body>
</html>