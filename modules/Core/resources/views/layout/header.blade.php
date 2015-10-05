<nav class="navbar navbar-default m-b-none">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">@lang('app.navigation.toggle')</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="/img/logo-sm.png" alt="@lang('app.title')">
            </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse m-r-n-md">
            <ul class="nav navbar-nav">
                <li><a href="/">Главная</a></li>

            </ul>

            <ul class="nav nav-profile navbar-nav pull-right">
                @if(Auth::check())
                    <li><a href="/auth/logout"><i class="fa fa-power-off"></i> @lang('auth.menu.logout')</a>
                @else
                    <li><a href="/auth/login">@lang('auth.menu.login')</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>

@if(isset($pageTitle))
    <div class="container m-b-sm">
        <h3 class="m-b-none">
            {{ $pageTitle or null }}
        </h3>
        <small>{{ $pageSubtitle or null }}</small>
    </div>
@endif