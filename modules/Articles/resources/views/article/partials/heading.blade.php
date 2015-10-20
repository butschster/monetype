<div class="media">
    <div class="media-left">
        {!! $article->author->getAvatar(35) !!}
    </div>
    <div class="media-body">
        <span class="pull-right">{!! $article->statusTitle !!}</span>

        {!! $article->author->getProfileLink() !!}<br>
        <small class="text-muted">{{ $article->published }}</small>
    </div>
</div>