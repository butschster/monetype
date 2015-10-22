@extends('core::layout.main')

@section('content')
    <script type="text/javascript" src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs" data-app-key="nf6y1tj9krdxzkr"></script>

    <h2>@lang('articles::article.title.create')</h2>

    {!! Form::model($article , [
		'route' => $action,
		'class' => 'panel',
		'method' => $article->exists ? 'PUT' : 'POST'
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

        <div class="panel-body">
            <div class="form-group">
                {!! Form::textarea('text_source', null, ['class' => 'form-control', 'rows' => 30, 'id' => 'inputText']) !!}
            </div>

            <hr class="panel-wide" />

            <div class="form-group">
                <label class="control-label">@lang('articles::article.field.tags')</label>
                {!! Form::textarea('tags', $tags, ['class' => 'form-control', 'id' => 'inputTags', 'rows' => 1]) !!}
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
                'type' => 'submit',
                'class' => 'btn btn-default btn-lg'
            ]) !!}
        </div>
    {!! Form::close() !!}
@endsection