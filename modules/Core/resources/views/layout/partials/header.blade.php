<nav class="navbar navbar-default" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-brand-centered">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="navbar-brand navbar-brand-centered">
                <a href="/">{!! HTML::image('img/logo.gif', trans('app.title')) !!}</a>
            </div>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbar-brand-centered">
            {{--<ul class="nav navbar-nav">--}}
            {{--<li><a href="#">Link</a></li>--}}
            {{--<li><a href="#">Link</a></li>--}}
            {{--<li><a href="#">Link</a></li>--}}
            {{--</ul>--}}
            <ul class="nav navbar-nav navbar-right">
                @if(Auth::check())
                    @if(Request::path() == 'article/create')
                        {{--<li><form>{!! link_to('article/create', trans('users::user.menu.write'), ['class'=>'btn btn-default navbar-btn']) !!}</form></li>--}}
                    @else
                        <li><form>{!! link_to('article/create', trans('users::user.menu.write'), ['class'=>'btn btn-default navbar-btn']) !!}</form></li>
                    @endif
                    <li class="dropdown @if(Request::is('profile')) active @endif">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding: 5px 15px;">
                            {!! $currentUser->getAvatar(50) !!}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#">@lang('users::user.label.balance', ['amount' => $currentUser->getBalance()])</a></li>
                            <li>{!! link_to_route('front.coupon.index', trans('users::user.menu.coupons')) !!}</li>

                            <li role="separator" class="divider"></li>
                            <li>{!! $currentUser->getProfileLink(trans('users::user.menu.profile')) !!}</li>
                            <li>{!! link_to_route('front.user.articles', trans('users::user.menu.articles')) !!}</li>

                            <li role="separator" class="divider"></li>
                            <li>{!! link_to('auth/logout', trans('users::user.menu.logout')) !!}</li>
                        </ul>
                    </li>
                @else
                    <li><form>{!! link_to('auth/register', trans('users::user.menu.register'), ['class'=>'btn btn-default navbar-btn']) !!}</form></li>
                    <li><form>{!! link_to('auth/login', trans('users::user.menu.login'), ['class'=>'btn btn-default navbar-btn']) !!}</form></li>
                @endif
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>


@if(isset($pageTitle1))
    <div class="container m-b-sm">
        <h3 class="m-b-none">
            {{ $pageTitle or null }}
        </h3>
        <small>{{ $pageSubtitle or null }}</small>
    </div>
@endif