<div class="media">
    <div class="media-body">
        <small>
            @lang('articles::article.field.author'): {{ $article->author->name }}
        </small>
        <br>
        <small class="text-muted">{{ $article->published }}</small>
    </div>
</div>