<nav class="navbar navbar-default">
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
                {!! HTML::image('img/logo-sm.png', trans('app.title')) !!}
            </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse m-r-n-md">
            <ul class="nav navbar-nav">

            </ul>

            <ul class="nav nav-profile navbar-nav pull-right">
                @if(Auth::check())
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            @lang('users::user.label.balance', ['amount' => $currentUser->getBalance()])
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                {!! link_to_route('front.coupon.index', trans('users::user.menu.coupons')) !!}
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown @if(Request::is('profile')) active @endif">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding: 5px 15px;">
                            {!! $currentUser->getAvatar(40) !!}
                        </a>
                        <ul class="dropdown-menu">
                            <li>{!! $currentUser->getProfileLink(trans('users::user.menu.profile')) !!}</li>
                            <li>{!! link_to_route('front.user.articles', trans('users::user.menu.articles')) !!}</li>
                            <li role="separator" class="divider"></li>
                            <li>{!! link_to('auth/logout', trans('users::user.menu.logout')) !!}</li>
                        </ul>
                    </li>
                @else
                    <li>{!! link_to('auth/login', trans('users::user.menu.login')) !!}</li>
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