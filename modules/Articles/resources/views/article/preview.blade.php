@extends('core::layout.main')

@section('content')
    <div class="panel">
        <div class="panel-heading">
            <h2>{{ $article->title }}</h2>
        </div>
        <div class="well well-border m-b-none">
            <label class="control-label">@lang('articles::article.field.text_intro')</label>
            {!! $article->text_intro !!}
        </div>
        <div class="panel-body">
            <label class="control-label">@lang('articles::article.field.text')</label>
            {!! $article->text !!}
            <hr class="panel-wide" />
            @include('articles::article.partials.tags', ['tags' => $tags, 'showTagsTitle' => true])
        </div>
        <div class="well well-border m-b-none">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        {!! Form::checkbox('', 1, $article->disable_comments, ['disabled']) !!}
                        @lang('articles::article.field.disable_comments')
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        {!! Form::checkbox('', 1, $article->disable_stat_views, ['disabled']) !!}
                        @lang('articles::article.field.disable_stat_views')
                    </label>

                </div>
                <div class="checkbox">
                    <label>
                        {!! Form::checkbox('', 1, $article->disable_stat_pays, ['disabled']) !!}
                        @lang('articles::article.field.disable_stat_pays')
                    </label>
                </div>
            </div>
        </div>
        <div class="panel-footer">
        @if($article->isDrafted())
            {!! Form::open(['route' => ['front.article.publish', $article->id], 'method' => 'put']) !!}
                {!! link_to_route('front.article.edit', trans('articles::article.button.edit'), $article->id, [
                    'class' => 'btn btn-default', 'data-icon' => 'pencil'
                ]) !!}
                {!! Form::button(trans('articles::article.button.publish'), [
                    'type' => 'submit',
                    'class' => 'btn btn-success pull-right', 'data-icon' => 'check'
                ]) !!}
            {!! Form::close() !!}
        @elseif($article->isPublished())
            {!! Form::open(['route' => ['front.article.draft', $article->id], 'method' => 'put']) !!}
                {!! link_to_route('front.article.show', trans('articles::article.button.view'), $article->id, [
                    'class' => 'btn btn-success', 'data-icon' => 'desktop'
                ]) !!}
                {!! Form::button(trans('articles::article.button.draft'), [
                    'type' => 'submit',
                    'class' => 'btn btn-danger pull-right', 'data-icon' => 'eye-off'
                ]) !!}
            {!! Form::close() !!}
        @endif
            <div class="clearfix"></div>
        </div>
    </div>
@endsection