@if(auth()->check())
<h3 class="commentForm--title" id="comment" data-icon="commenting-o">
    <a href="#">@lang('comments::comment.title.new')</a>
</h3>

{!! Form::open(['route' => ['front.comment.post', $article->id], 'class' => 'commentForm', 'id' => 'commentForm']) !!}
    {!! Form::hidden('parent_id', null, ['id' => 'commentParentId']) !!}

    <div class="form-group">
        {!! Form::text('title', null, [
            'class' => 'form-control', 'id' => 'commentTitle', 'placeholder' => trans('comments::comment.field.title')
        ]) !!}
    </div>

    <div class="form-group">
        {!! Form::textarea('text', null, [
            'class' => 'form-control', 'id' => 'commentText', 'rows' => 4, 'placeholder' => trans('comments::comment.field.text')
        ]) !!}
    </div>

    {!! Form::button(trans('comments::comment.button.send'), ['type' => 'submit', 'class' => 'btn btn-success', 'data-icon' => 'comment']) !!}
{!! Form::close() !!}
@else
    <h4 class="alert alert-info">@lang('comments::comment.message.need_to_auth')</h4>
@endif