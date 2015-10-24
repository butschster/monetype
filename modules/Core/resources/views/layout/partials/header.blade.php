<nav class="navbar navbar-contrast">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ url('/') }}">
                {!! HTML::image('img/logo.gif', trans('app.title'), ['style' => 'height: 40px']) !!}
            </a>
        </div>
        <div id="navbar">
            {!! Form::open(['route' => 'front.search', 'class' => 'navbar-form navbar-search navbar-left', 'method' => 'get']) !!}
                <div class="form-group">
                    {!! Form::text('query', null, ['class' => 'form-control', 'placeholder' => trans('articles::article.label.search')]) !!}
                </div>
            {!! Form::close() !!}

            <ul class="nav nav-profile navbar-nav pull-right">
                @if(Auth::check())
                    <li>
                        {!! link_to_route('front.article.create', trans('articles::article.menu.create'), [], ['class' => 'nav-circle-li']) !!}
                    </li>
                    <li class="dropdown @if(Request::is('profile')) active @endif">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding: 5px 15px;">
                            {!! $currentUser->getAvatar(40) !!}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li class="nav-profile--balance @if($currentUser->balance > 0) positive @endif">
                                @lang('users::user.label.balance', ['amount' => $currentUser->getBalance()])
                            </li>
                            <li>{!! link_to_route('front.coupon.index', trans('users::user.menu.coupons')) !!} </li>
                            <li role="separator" class="divider"></li>
                            <li>{!! $currentUser->getProfileLink(trans('users::user.menu.profile')) !!}</li>
                            <li role="separator" class="divider"></li>
                            <li>{!! link_to_route('front.user.articles', trans('users::user.menu.articles')) !!}</li>
                            <li>{!! link_to_route('front.user.bookmarks', trans('users::user.menu.bookmarks')) !!}</li>
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