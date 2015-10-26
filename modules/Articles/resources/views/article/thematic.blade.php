@extends('core::layout.main')

@section('content')
    <h2 class="page-header">{!! $pageTitle !!}</h2>

    @if(empty($tags))
        <h4 class="alert alert-info">@lang('articles::article.message.no_thematic_tags')</h4>
    @endif

    <div class="m-t-md well well-sm">
        {!! Form::text('tag', null, [
            'class' => 'form-control',
            'placeholder' => 'Add thematic tag...',
            'id' => 'addTagInput',
            'autocomplete' => 'off'
        ]) !!}

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