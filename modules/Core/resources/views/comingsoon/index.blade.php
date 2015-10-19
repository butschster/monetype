@extends('core::layout.empty')

@section('content')
    <div class="header-container">
        <div class="container">
            {!! HTML::image('img/logo.gif', null, ['class' => 'header-logo']) !!}
            <p class="lead">@lang('core::comingsoon.aboutProject')</p>
        </div>
        <div class="coming-soon-border"></div>
    </div>
    <div class="cooming-soon-content">
        <div class="coming-soon-bg-cover"></div>
        <div class="container">
            <div class="row coming-soon">
                <div class="col-md-7 col-sm-5 text text-left">
                    <h3 style="margin-top: -10px;">@lang('core::comingsoon.howWorksTitle')</h3>
                    <p>@lang('core::comingsoon.howWorksText', ['cost' => 1, 'choise' => Lang::choice('core::comingsoon.articleCostValue', 1)])</p>
                </div>
                <div class="col-md-5 col-sm-7 form-box wow fadeInUp animated" style="visibility: visible;">
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
                        @include('core::comingsoon.registerForm')
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="coming-soon-border"></div>
    <div class="section-container section-container-gray-bg">
        <div class="container">
            <h2 class="text-center">@lang('core::comingsoon.startProject')</h2>

            <div class="coming-soon-plugin">
                <div id="defaultCountdown"></div>
            </div>

        </div>
    </div>

    <p class="copyright-space">
        {{ date('Y') }} © MoneType. ALL Rights Reserved.
    </p>
@stop