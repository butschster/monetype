<h2>Comments</h2>
<div class="panel">
    <div class="panel-body comments">
        @foreach($article->comments as $comment)
            <div class="media comment-item">
                <div class="media-left media-middle user-avatar">
                    {!! $comment->author->getAvatar(50, ['class' => 'media-object']) !!}
                </div>
                <div class="media-body comments-itself">
                    <h4 class="media-heading">{!! $comment->author->getProfileLink() !!}</h4>
                    <small class="text-muted">{{ $comment->created }}</small>
                    <br />
                    {!! $comment->text !!}
                </div>
            </div>
            <hr />
        @endforeach
    </div>
</div>
