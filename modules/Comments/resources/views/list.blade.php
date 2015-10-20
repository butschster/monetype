<div class="commentList">
    @if($article->comments->count() > 0)
    <div class="container">
        <h2 class="commentList--title" id="comments">@lang('comments::comment.title.list', ['size' => $article->comments->count()])</h2>

        @include('comments::partials.list', ['comments' => $article->comments()->with('author')->get()->toHierarchy()])
    </div>
    <hr />
    @endif

    <div class="container">
        @include('comments::partials.form', ['article' => $article])
    </div>
</div>