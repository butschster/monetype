@extends('core::layout.empty')

@section('content')
	<div class="login-container">
		<div class="login-header text-center m-b-xl">
			<div class="brand">
				{!! link_to_route('front.main', HTML::image('img/logo.png', null, ['width' => '200px', 'class' => 'header-logo'])) !!}
			</div>
		</div>
		<div class="login-content">
			@include('users::auth.partials.registerForm')
		</div>
	</div>
</div>
@endsection
