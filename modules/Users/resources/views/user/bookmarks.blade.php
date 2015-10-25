@extends('core::layout.main')

@section('content')
    <h2 class="page-header">@lang('users::user.title.bookmarks')</h2>

    @if($articles->count() > 0)
    <div class="well">
        {!! Form::text('query', null, ['id' => 'searchBookmarked', 'class' => 'form-control', 'placeholder' => trans('users::user.label.bookmark_search')]) !!}
    </div>
    @endif

    @include('articles::article.partials.list', ['emptyMessage' => trans('users::user.message.empty_bookmarks')])
@endsection