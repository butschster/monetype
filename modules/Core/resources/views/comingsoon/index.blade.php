@extends('core::layout.empty')

@section('content')
    <div class="cooming-soon-content">
        <div class="container header-container">
            {!! HTML::image('img/logo.gif', null, ['class' => 'header-logo']) !!}

        </div>

        <div class="coming-soon-bg-cover"></div>

        <div class="container">
            <div class="coming-soon">
                <div class="text">
                    <h2 class="m-t-none m-b-lg">@lang('core::comingsoon.aboutProject')</h2>
                </div>

                <div class="row">
                    <div class="col-md-7 text text-left">
                        <h3 style="margin-top: -10px;">@lang('core::comingsoon.howWorksTitle')</h3>
                        <p>@lang('core::comingsoon.howWorksText')</p>

                        <div class="hidden-sm hidden-xs">
                            <br /><br /><br /><br />
                        </div>

                        <h3>@lang('core::comingsoon.startProject')</h3>
                        <div class="coming-soon-plugin">
                            <div id="defaultCountdown"></div>
                        </div>

                        <div class="visible-sm visible-xs">
                            <br /><br />
                        </div>
                    </div>
                    <div class="col-md-5 wow fadeInUp animated" style="visibility: visible;">
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
            </div>
        </div>
    </div>
    <div class="coming-soon-border"></div>
    <div class="section-container section-container-gray-bg">
        <div class="container">


        </div>
    </div>

    <p class="copyright-space">
        {{ date('Y') }} © MoneType. ALL Rights Reserved.
    </p>
@stop