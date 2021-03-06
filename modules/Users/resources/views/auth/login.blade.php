@extends('core::layout.empty')

@section('content')
<div class="login-container">
	<div class="login-header text-center m-b-xl">
		<div class="brand">
			{!! link_to_route('front.main', HTML::image('img/logo.png', null, ['width' => '200px', 'class' => 'header-logo'])) !!}
		</div>
	</div>
	<div class="login-content">
		{!! Form::open(['url' => 'auth/login', 'class' => 'panel m-b-none']) !!}

			@if (count($errors) > 0)
			<ul class="alert alert-warning list-unstyled">
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
			@endif

			<div class="panel-body">
				<div class="form-group">
					<div class="input-icon-append">
						<i class="icon-append icon-envelope-o"></i>
						{!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => trans('users::user.field.email')]) !!}
					</div>
				</div>
				<div class="form-group">
					<div class="input-icon-append">
						<i class="icon-append icon-lock"></i>
						{!! Form::password('password', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => trans('users::user.field.password')]) !!}
					</div>
				</div>
				<div class="form-group m-b-none">
					<div class="checkbox m-b-none">
						<label>
							{!! Form::checkbox('remember') !!} @lang('users::user.field.remember')
						</label>
					</div>
				</div>
			</div>
			<div class="panel-footer">
				{!! Form::button(trans('users::user.button.login'), ['class' => 'btn btn-success', 'type' => 'submit', 'data-icon' => 'check']) !!}

				{!! link_to('password/email', trans('users::user.button.forget_password'), ['class' => 'btn btn-link']) !!}
			</div>
		{!! Form::close() !!}
	</div>
</div>
@endsection
