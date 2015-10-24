@extends('core::layout.main')

@section('content')
    <h2>@lang('articles::article.title.by_tag', ['tag' => $tag])</h2>

    @if(!is_null($tagsCloud))
    <div class="row">
        <div class="col-sm-7">
            @include('articles::article.partials.list')
        </div>
        <div class="col-sm-5">

            @include('articles::tag.partials.tagscloud')

        </div>
    </div>
    @else
        @include('articles::article.partials.list', ['emptyMessage' => trans('articles::tag.message.no_search_results', ['tag' => $tag])])
    @endif
@endsection