@extends('core::layout.main')

@section('content')
    <link rel="stylesheet" href="//cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    <script src="//cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>

    <script>
        $(function() {
            new SimpleMDE({
                element: document.getElementById('inputText')
            });
        });
    </script>
    <h2>@lang('articles::article.title.create')</h2>

    {!! Form::model($article , [
		'route' => $action,
		'class' => 'panel',
    ]) !!}

        @if (count($errors) > 0)
        <ul class="alert alert-warning m-b-none">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif

        <div class="panel-heading">
            <div class="form-group form-group-lg">
                <label class="control-label">@lang('articles::article.field.title')</label>
                {!! Form::text('title', null, ['class' => 'form-control', 'id' => 'inputTitle']) !!}
            </div>

            <div class="form-group">
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('forbid_comment') !!}
                            @lang('articles::article.field.forbid_comment')
                        </label>
                    </div>
            </div>
        </div>

        <div class="panel-body">
            <div class="form-group">
                {!! Form::textarea('text_source', null, ['class' => 'form-control', 'rows' => 30, 'id' => 'inputText']) !!}
            </div>
            <hr class="panel-wide" />
            @include('articles::article.partials.inputTags')
        </div>

        <div class="panel-footer">
            {!! Form::button(trans('articles::article.button.draft'), [
                'type' => 'submit',
                'class' => 'btn btn-default btn-lg'
            ]) !!}
        </div>
    {!! Form::close() !!}
@endsection