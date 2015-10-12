<div class="media">
    <div class="media-body">
        <small>
            {!! $article->author->getAvatar(30) !!}  {!! $article->author->getProfileLink() !!}
        </small>
        <br>
        <small class="text-muted">{{ $article->published }}</small>
    </div>
</div>