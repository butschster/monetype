<nav class="navbar navbar-contrast">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ url('/') }}">
                {!! HTML::image('img/logo.gif', trans('app.title'), ['style' => 'height: 40px']) !!}
            </a>
        </div>
        <div id="navbar" class="m-r-n-md">
            <ul class="nav nav-profile navbar-nav pull-right">
                @if(Auth::check())
                    <li>
                        {!! link_to_route('front.article.create', trans('articles::article.menu.create'), [], ['class' => 'nav-circle-li']) !!}
                    </li>
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