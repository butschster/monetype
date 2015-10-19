<div class="commentList">
    <div class="container">
        <h2 class="commentList--title">@lang('comments::comment.title.list', ['size' => $article->comments->count()])</h2>
        @foreach($article->comments as $comment)
        <div class="media commentItem">
            <div class="media-left user-avatar">
                {!! $comment->author->getAvatar(35, ['class' => 'media-object img-circle']) !!}
            </div>
            <div class="media-body comments-itself">
                {!! $comment->author->getProfileLink() !!}<br />
                <small class="text-muted">{{ $comment->created }}</small>
            </div>

            <div class="commentItem--content clearfix">
                {!! $comment->text !!}
            </div>
        </div>
        @endforeach
    </div>
</div>