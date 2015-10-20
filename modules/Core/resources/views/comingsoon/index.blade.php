@extends('core::layout.empty')

@section('content')
    <div class="comingSoon">
        <div class="container comingSoon--header">
            {!! HTML::image('img/logo.gif', null, ['class' => 'header-logo']) !!}
        </div>

        <div class="comingSoon--bg-cover"></div>

        <div class="container">
            <div class="comingSoon--content">
                <div class="comingSoon--text">
                    <h2 class="m-t-none m-b-lg">@lang('core::comingsoon.aboutProject')</h2>
                </div>

                <div class="comingSoon--left">
                    <div class="comingSoon--about">
                        <h3 style="margin-top: -10px;">@lang('core::comingsoon.howWorksTitle')</h3>
                        <p>@lang('core::comingsoon.howWorksText')</p>
                    </div>
                </div>

                <div class="comingSoon--right">
                    <div class="comingSoon--register">
                        @if(auth()->check())
                            <div class="well">
                                <div class="headline">
                                    <h3>@lang('core::comingsoon.thankRegistration')</h3>
                                </div>

                                <hr />
                                <p>@lang('core::comingsoon.startBalance', ['balance' => $currentUser->balance])</p>
                                <p>@lang('core::comingsoon.startNotification')</p>
                            </div>
                        @else
                            @include('users::auth.partials.registerForm')
                        @endif
                    </div>
                </div>

                <div class="comingSoon--left comingSoon--countDown">
                    <div class="comingSoon--text">
                        <h3>@lang('core::comingsoon.startProject')</h3>
                        <div id="countDown"></div>
                    </div>
                </div>

                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <div class="comingSoon--border"></div>

    <p class="copyright-space">
        {{ date('Y') }} © MoneType. ALL Rights Reserved.
    </p>
@stop