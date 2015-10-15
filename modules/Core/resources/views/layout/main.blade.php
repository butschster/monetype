@include('core::layout.partials.meta')

<body id="body.{{ $bodyId }}">
    @include('core::layout.partials.header')

    @yield('header.content')

    <div id="contentContainer" class="container">
        @yield('content')
    </div>

    @yield('footer.content')

    @include('core::layout.partials.footer')
</body>
</html>