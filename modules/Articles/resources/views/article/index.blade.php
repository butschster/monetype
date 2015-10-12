@extends('core::layout.main')

@section('content')
    <h2>@lang('articles::article.title.list')</h2>

    @include('articles::article.partials.list')
@endsection