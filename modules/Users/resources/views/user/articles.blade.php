@extends('core::layout.main')

@section('content')
    <h2 class="page-header">@lang('users::user.title.articles')</h2>

    @include('articles::article.partials.list')
@endsection