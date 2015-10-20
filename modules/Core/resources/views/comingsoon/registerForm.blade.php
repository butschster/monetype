{!! Form::open([
    'class' => 'form', 'url' => 'auth/register', 'id' => 'registerForm'
]) !!}
    <header>
        <h3>@lang('users::user.title.registration')</h3>

        @if($couponsCount > 0)
        <p>@lang('core::comingsoon.registerBonus', ['count' => $couponsCount, 'amount' => 10])</p>
        @endif
    </header>

    @if (count($errors) > 0)
    <ul class="alert alert-warning list-unstyled">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    @endif

    <fieldset>
        <div class="form-group">
            <label class="sr-only" for="form-email">@lang('users::user.field.username')</label>

            <div class="input-icon-append">
                <i class="icon-append fa fa-user"></i>

                {!! Form::text('username', null, [
                    'class' => 'form-control', 'placeholder' => trans('users::user.field.username'), 'id' => 'form-name'
                ]) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="sr-only" for="form-email">@lang('users::user.field.email')</label>

            <div class="input-icon-append">
                <i class="icon-append fa fa-envelope-o"></i>

                {!! Form::input('email', 'email', null, [
                    'class' => 'form-email form-control', 'placeholder' => trans('users::user.field.email'), 'id' => 'form-email'
                ]) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="sr-only">@lang('users::user.field.password')</label>

            <div class="input-icon-append">
                <i class="icon-append fa fa-lock"></i>

                {!! Form::password('password', [
                   'class' => 'form-control', 'placeholder' => trans('users::user.field.password'), 'id' => 'form-password'
                ]) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="sr-only">@lang('users::user.field.password_confirm')</label>

            <div class="input-icon-append">
                <i class="icon-append fa fa-lock"></i>

                {!! Form::password('password_confirmation', [
                   'class' => 'form-control', 'placeholder' => trans('users::user.field.password_confirm'), 'id' => 'form-password_confirm'
                ]) !!}
            </div>
        </div>
        <div class="form-group">
            <div class="g-recaptcha" data-sitekey="6LexrAMTAAAAAH2Bnzt6CL4fM_gETrnZ43wmRml4"></div>
        </div>
    </fieldset>
    <footer>
        <button type="submit" class="btn btn-primary">@lang('core::comingsoon.giveMoney')</button>
    </footer>
{!! Form::close() !!}