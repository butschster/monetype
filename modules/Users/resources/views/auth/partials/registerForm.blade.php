<script src='https://www.google.com/recaptcha/api.js'></script>

{!! Form::open(['url' => 'auth/register', 'class' => 'panel m-b-none']) !!}

@if($couponsCount > 0)
    <div class="alert alert-info m-b-none">
        <p class="text-uppercase">@lang('core::comingsoon.registerBonus', ['count' => $couponsCount, 'amount' => 10])</p>
    </div>
@endif
<div class="panel-body">
    <div class="form-group">
        <div class="input-icon-append">
            <i class="icon-append icon-mail"></i>
            {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => trans('users::user.field.email')]) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="input-icon-append">
            <i class="icon-append icon-lock"></i>
            {!! Form::password('password', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => trans('users::user.field.password')]) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="input-icon-append">
            <i class="icon-append icon-lock"></i>
            {!! Form::password('password_confirmation', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => trans('users::user.field.password_confirm')]) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="g-recaptcha" data-sitekey="6LexrAMTAAAAAH2Bnzt6CL4fM_gETrnZ43wmRml4"></div>
    </div>

    <hr class="panel-wide" />

    @if($couponsCount > 0)
        <button type="submit" class="btn btn-info btn-lg" data-icon="gift icon-lg">@lang('users::user.button.giveMoney')</button>
    @else
        {!! Form::button(trans('users::user.button.register'), [
            'class' => 'btn btn-info btn-lg', 'type' => 'submit', 'data-icon' => 'thumbs-o-up icon-lg'
        ]) !!}
    @endif
</div>
{!! Form::close() !!}