@extends('core::layout.main')

@section('content')
    <h2 class="page-header">@lang('articles::article.title.list')</h2>

    <div class="row">
        <div class="col-sm-7">
            @include('articles::article.partials.list')
        </div>
        <div class="col-sm-5">
            @include('articles::tag.partials.tagscloud')
        </div>
    </div>

@endsection