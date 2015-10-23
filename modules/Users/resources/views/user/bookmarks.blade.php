@extends('core::layout.main')

@section('content')
    <h2 class="page-header">@lang('users::user.title.bookmarks')</h2>

    @include('articles::article.partials.list', ['emptyMessage' => trans('users::user.message.empty_bookmarks')])
@endsection