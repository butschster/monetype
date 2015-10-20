@foreach($comments as $comment)
    <div class="media commentItem" data-id="{{ $comment->id }}" id="comment_{{ $comment->id }}">
        <div class="commentItem--body">
            <div class="media-left user-avatar">
                {!! $comment->author->getAvatar(20, ['class' => 'media-object img-circle']) !!}
            </div>
            <div class="media-body comments-itself">
                {!! $comment->author->getProfileLink() !!}
                <small class="text-muted">{{ $comment->created }}</small>
            </div>

            <div class="commentItem--content clearfix">
                @if(!empty($comment->title))
                    <h4>{{ $comment->title }}</h4>
                @endif
                {!! $comment->text !!}
            </div>
        </div>

        <div class="commentItem--meta text-right">
            <a href="#comment" class="commentItem--reply" data-id="{{ $comment->id }}">@lang('comments::comment.button.reply')</a>
        </div>

        @if($comment->children->count() > 0)
            <div class="commentItem--replies">
                @include('comments::partials.list', ['comments' => $comment->children])
            </div>
        @endif
    </div>
@endforeach