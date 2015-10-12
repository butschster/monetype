<div class="media">
    <div class="media-left">
        {!! $article->author->getAvatar(40) !!}
    </div>
    <div class="media-body">
        {!! $article->author->getProfileLink() !!}<br>
        <small class="text-muted">{{ $article->published }}</small>
    </div>
</div>