<span class="articleItem--favorites addToFavorite @if($article->is_favorited) articleItem--bookmarked @endif" data-id="{{ $article->id }}">
    <i class="icon-bookmark"></i>
    {{--
    <span class="articleItem--favorites-count" title="@lang('articles::article.label.favorites')">
        {{ $article->count_favorites }}
    </span>
    --}}
</span>