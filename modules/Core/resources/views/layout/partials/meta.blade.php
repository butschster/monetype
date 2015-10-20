<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
	<title>{{ $title or trans('core::core.title.app') }}</title>
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="csrf-token" content="{{ csrf_token() }}" />

	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"/>

	{!! Assets::group('global', 'layoutScripts') !!}
	{!! Assets::css(null,'/public/app_new.css') !!}
	{!! Assets::js() !!}
	{!! Assets::group('global', 'layoutEvents') !!}

	@yield('scripts')
</head>