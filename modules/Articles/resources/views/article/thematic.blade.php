@extends('core::layout.main')

@section('header.content')
    <div class="container">
        <h2 class="page-header">{!! $pageTitle !!}</h2>
    </div>

    @include('articles::article.partials.subnav')
@endsection

@section('content')

    @if(empty($tags))
        <h4 class="alert alert-info">@lang('articles::article.message.no_thematic_tags')</h4>
    @endif

    {!! Form::text('tag', null, [
        'class' => 'form-control',
        'placeholder' => trans('articles::tag.label.input_thematic_tag'),
        'id' => 'addTagInput',
        'autocomplete' => 'off'
    ]) !!}
    <div class="m-t-md well well-sm">
        <div class="tagsCloud" id="thematicTags">
            @include('articles::tag.partials.thematic')
        </div>
    </div>

    <div id="thematicArticles">
        @if(!empty($articles))
            @include('articles::article.partials.list', ['emptyMessage' => trans('articles::article.message.empty_thematic_list')])
        @else
            <h4 class="alert alert-info">@lang('articles::article.message.empty_thematic_list')</h4>
        @endif
    </div>
@endsection