@extends('core::layout.main')

@section('content')
    <h2>@lang('articles::article.title.create')</h2>

    {!! Form::model($article , [
		'route' => $action,
		'class' => 'panel form-horizontal',
    ]) !!}

        @if (count($errors) > 0)
        <ul class="alert alert-warning">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif

        <div class="panel-body">
            <div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">@lang('articles::article.field.title')</label>
                <div class="col-sm-10">
                    {!! Form::text('title', null, ['class' => 'form-control', 'id' => 'inputTitle']) !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">@lang('articles::article.field.text_intro')</label>
                <div class="col-sm-10">
                    {!! Form::textarea('text_intro', null, ['class' => 'form-control', 'rows' => 3, 'id' => 'inputTextIntro']) !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">@lang('articles::article.field.text')</label>
                <div class="col-sm-10">
                    {!! Form::textarea('text', null, ['class' => 'form-control', 'rows' => 10, 'id' => 'inputText']) !!}
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-10 col-lg-offset-2">
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('forbid_comment') !!}
                            @lang('articles::article.field.forbid_comment')
                        </label>
                    </div>
                </div>
            </div>

            @include('articles::article.partials.inputTags')
        </div>

        <div class="panel-footer">
            <div class="form-group m-b-none">
                <div class="col-sm-offset-2 col-sm-10">
                    {!! Form::button(trans('articles::article.button.draft'), [
                        'type' => 'submit',
                        'class' => 'btn btn-default btn-lg'
                    ]) !!}
                </div>
            </div>
        </div>
    {!! Form::close() !!}
@endsection