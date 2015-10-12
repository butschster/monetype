@extends('core::layout.main')

@section('content')
    <h2>@lang('articles::article.title.by_tag', ['tag' => $tag])</h2>

    @include('articles::article.partials.list')
@endsection