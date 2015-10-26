@extends('core::layout.main')

@section('content')
    <script type="text/javascript" src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs" data-app-key="nf6y1tj9krdxzkr"></script>

    <h2 class="page-">{{ $pageTitle }}</h2>

    {!! Form::model($article , [
		'route' => $action,
		'class' => 'panel',
		'method' => $article->exists ? 'PUT' : 'POST',
		'id' => 'articleForm'
    ]) !!}

        @if (count($errors) > 0)
        <ul class="alert alert-warning m-b-none list-unstyled">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif

        <div class="well well-border m-b-none">
            <div class="form-group form-group-lg">
                <label class="control-label">@lang('articles::article.field.title')</label>
                {!! Form::text('title', null, ['class' => 'form-control', 'id' => 'inputTitle']) !!}
            </div>
        </div>

        @if(!$currentUser->can($ability, $article))
        <div class="well well-border m-b-none">
            <label class="control-label">@lang('articles::article.field.text_intro')</label>
            {!! $article->text_intro !!}

            @if($article->hasReadModerButton())
            <a href="#" class="btn btn-sm btn-default">{{ $article->read_more_text }}</a>
            @endif
        </div>
        @endif

        <div class="panel-body">
            @if($currentUser->can($ability, $article))
            <div class="form-group">
                {!! Form::textarea('text_source', null, ['class' => 'form-control', 'rows' => 30, 'id' => 'inputText']) !!}
            </div>
            @else
                <label class="control-label">@lang('articles::article.field.text')</label>
                {!! $article->text !!}
            @endif
            <hr class="panel-wide" />

            <div class="form-group">
                <label class="control-label">@lang('articles::article.field.tags_list')</label>
                {!! Form::select('tags_list', $tags, $tags, ['multiple', 'data-role' => 'tagsinput']) !!}
            </div>

            <hr class="panel-wide" />

            <div class="form-group">
                <label class="control-label">
                    @lang('articles::article.field.cost')

                    <span id="slider-cost">
                        <span class="slider-value"></span>
                        <span data-icon="rouble"></span>
                    </span>
                </label>
                {!! Form::hidden('cost') !!}
            </div>

            <hr class="panel-wide" />

            <div class="form-group">
                <div class="checkbox">
                    <label>
                        {!! Form::hidden('disable_comments', 0) !!}
                        {!! Form::checkbox('disable_comments') !!}
                        @lang('articles::article.field.disable_comments')
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        {!! Form::hidden('disable_stat_views', 0) !!}
                        {!! Form::checkbox('disable_stat_views') !!}
                        @lang('articles::article.field.disable_stat_views')
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        {!! Form::hidden('disable_stat_pays', 0) !!}
                        {!! Form::checkbox('disable_stat_pays') !!}
                        @lang('articles::article.field.disable_stat_pays')
                    </label>
                </div>
            </div>
        </div>

        <div class="panel-footer">
            {!! Form::button(trans('articles::article.button.save'), [
               'type' => 'submit', 'value' => 'save',
               'class' => 'btn btn-default btn-lg', 'data-icon' => 'ok'
            ]) !!}

            @if($article->isDrafted())
            {!! Form::button(trans('articles::article.button.publish'), [
                'type' => 'submit', 'value' => 'publish',
                'class' => 'btn btn-success pull-right', 'data-icon' => 'thumbs-up'
            ]) !!}
            @elseif($article->isPublished())
            {!! Form::button(trans('articles::article.button.draft'), [
                'type' => 'submit', 'value' => 'draft',
                'class' => 'btn btn-danger pull-right', 'data-icon' => 'thumbs-down'
            ]) !!}
            @endif
        </div>
    {!! Form::close() !!}
@endsection