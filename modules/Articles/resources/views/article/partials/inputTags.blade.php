<div class="form-group">
    <label class="control-label">@lang('articles::article.field.tags')</label>
    {!! Form::textarea('tags', $tags, ['class' => 'form-control', 'id' => 'inputTags', 'rows' => 1]) !!}
</div>