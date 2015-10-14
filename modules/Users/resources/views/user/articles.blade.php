@extends('core::layout.main')

@section('content')
    <h2>@lang('users::user.title.articles')</h2>

    @include('articles::article.partials.list')
@endsection