@extends('core::layout.main')

@section('content')
    <h2>@lang('articles::article.title.money', ['article' => $article->title])</h2>

    @include('transactions::list')
@endsection