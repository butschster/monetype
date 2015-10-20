@extends('core::layout.empty')

@section('content')
<div class="login-container">
	<div class="login-header text-center m-b-xl">
		<div class="brand">
			{!! link_to_route('front.main', HTML::image('img/logo.png', null, ['width' => '200px', 'class' => 'header-logo'])) !!}
		</div>
	</div>
	<div class="login-content">
		{!! Form::open(['url' => 'password/reset', 'class' => 'panel m-b-none']) !!}
		{!! Form::hidden('token', $token) !!}
		<div class="panel-body">

			<h2 class="m-t-none">@lang('users::user.title.reset_password')</h2>
			<hr class="panel-wide" />

			@if (count($errors) > 0)
			<ul class="alert alert-warning list-unstyled">
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
			@endif

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
					{!! Form::password('password_confirmation', ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => trans('users::user.field.password_confirm')]) !!}
				</div>
			</div>
		</div>
		<div class="panel-footer">
			{!! Form::button(trans('users::user.button.reset_password'), ['class' => 'btn btn-info', 'type' => 'submit', 'data-icon' => 'check']) !!}
		</div>
		{!! Form::close() !!}
	</div>
</div>
@endsection