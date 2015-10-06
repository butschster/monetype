@extends('core::layout.empty')

@section('content')
	<script src='https://www.google.com/recaptcha/api.js'></script>
	<div class="login-container">
		<div class="login-header text-center m-b-xl">
			<div class="brand">
				{!! link_to_route('front.main', HTML::image('img/logo.png', null, ['width' => '200px', 'class' => 'header-logo'])) !!}
			</div>
		</div>
		<div class="login-content">
			{!! Form::open(['url' => 'auth/register', 'class' => 'form']) !!}

				@if (count($errors) > 0)
				<ul class="alert alert-warning">
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
				@endif

				<fieldset>
					<div class="form-group">
						<div class="input-icon-append">
							<i class="icon-append fa fa-user"></i>
							{!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('users::user.field.name')]) !!}
						</div>
					</div>
					<div class="form-group">
						<div class="input-icon-append">
							<i class="icon-append fa fa-envelope-o"></i>
							{!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => trans('users::user.field.email')]) !!}
						</div>
					</div>

					<div class="form-group">
						<div class="input-icon-append">
							<i class="icon-append fa fa-lock"></i>
							{!! Form::password('password', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => trans('users::user.field.password')]) !!}
						</div>
					</div>

					<div class="form-group">
						<div class="input-icon-append">
							<i class="icon-append fa fa-lock"></i>
							{!! Form::password('password_confirm', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => trans('users::user.field.password_confirm')]) !!}
						</div>
					</div>

					<div class="form-group">
						<div class="g-recaptcha" data-sitekey="6LexrAMTAAAAAH2Bnzt6CL4fM_gETrnZ43wmRml4"></div>
					</div>
				</fieldset>
				<footer>
					{!! Form::button(trans('users::user.button.register'), ['class' => 'btn btn-primary', 'type' => 'submit']) !!}
				</footer>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection
