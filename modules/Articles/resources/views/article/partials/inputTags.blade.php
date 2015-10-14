<div class="form-group">
    <label class="col-sm-2 control-label">@lang('articles::article.field.tags')</label>
    <div class="col-sm-10">
        {!! Form::textarea('tags', $tags, ['class' => 'form-control', 'id' => 'inputTags']) !!}
    </div>
</div>