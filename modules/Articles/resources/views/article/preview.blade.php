@extends('core::layout.main')

@section('content')
    <div class="panel">
        <div class="panel-heading">
            <div class="form-group form-group-lg">
                <label class="control-label">@lang('articles::article.field.title')</label>
                {!! Form::text('', $article->title, ['class' => 'form-control', 'id' => 'inputTitle', 'disabled']) !!}
            </div>

            <div class="form-group">
                <div class="checkbox">
                    <label>
                        {!! Form::checkbox('', 1, $article->forbid_comment, ['disabled']) !!}
                        @lang('articles::article.field.forbid_comment')
                    </label>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <label class="control-label">@lang('articles::article.field.text_intro')</label>
            {!! $article->text_intro !!}

            <hr class="panel-wide" />

            <label class="control-label">@lang('articles::article.field.text')</label>
            {!! $article->text !!}

            <hr class="panel-wide" />

            @include('articles::article.partials.tags', ['tags' => $tags, 'showTagsTitle' => true])
        </div>
        <div class="panel-footer">
        @if($article->isDrafted())
            {!! Form::open(['route' => ['front.article.publish', $article->id], 'method' => 'put']) !!}
                {!! link_to_route('front.article.edit', trans('articles::article.button.edit'), $article->id, ['class' => 'btn btn-default btn-lg']) !!}
                {!! Form::button(trans('articles::article.button.publish'), [
                    'type' => 'submit',
                    'class' => 'btn btn-success btn-lg'
                ]) !!}
            {!! Form::close() !!}
        @elseif($article->isPublished())
            {!! Form::open(['route' => ['front.article.draft', $article->id], 'method' => 'put']) !!}
                {!! Form::button(trans('articles::article.button.draft'), [
                    'type' => 'submit',
                    'class' => 'btn btn-default'
                ]) !!}
            {!! Form::close() !!}
        @endif
        </div>
    </div>
@endsection