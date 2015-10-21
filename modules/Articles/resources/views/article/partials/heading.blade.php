<div class="media">
    <div class="media-left">
        {!! $article->author->getAvatar(35) !!}
    </div>
    <div class="media-body">
        <div class="pull-right articleItem--favorites">
            @include('articles::article.partials.favorites')
        </div>

        {!! $article->author->getProfileLink() !!}<br>
        <small class="text-muted">{{ $article->published }}</small>
    </div>
</div>