@extends('core::layout.empty')

@section('content')
<div class="login-container">
	<div class="login-header text-center m-b-xl">
		<div class="brand">
			{!! link_to_route('front.main', HTML::image('img/logo.png', null, ['width' => '200px', 'class' => 'header-logo'])) !!}
		</div>
	</div>
	<div class="login-content">
		{!! Form::open(['url' => 'password/email', 'class' => 'form']) !!}

		<fieldset>
			<h2 class="m-t-n">Reset Password</h2>

			@if (session('status'))
			<div class="alert alert-success">
				{{ session('status') }}
			</div>
			@endif

			@if (count($errors) > 0)
			<ul class="alert alert-warning">
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
		</fieldset>
		<footer>
			{!! Form::button(trans('users::user.button.send_password'), ['class' => 'btn btn-info', 'type' => 'submit']) !!}
		</footer>
		{!! Form::close() !!}
	</div>
</div>
@endsection
