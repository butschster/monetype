<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
	<meta name="csrf-token" content="{{ csrf_token() }}" />

	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"/>

	{!! Assets::group('global', 'layoutScripts') !!}

	{!! Meta::build() !!}

	{!! Assets::group('global', 'layoutEvents') !!}

	@yield('scripts')

	@if(config('app.debug') or (auth()->check() and $currentUser->isAdmin()))
	<script>
		((window.gitter = {}).chat = {}).options = {
			room: 'butschster/monetype'
		};
	</script>
	<script src="https://sidecar.gitter.im/dist/sidecar.v1.js" async defer></script>
	@endif
</head>