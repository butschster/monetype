@forelse($articles as $article)
<div class="panel">
    <div class="panel-heading">
        @include('articles::article.partials.heading')
    </div>
    <div class="panel-body">
        <div class="media">
            <div class="media-body">
                <h3 class="media-heading">
                    {!! HTML::linkRoute('article.show', $article->title, ['id' => $article->id]) !!}
                </h3>

                <div class="media-content m-t-md">
                    {!! $article->text_intro !!}
                </div>
            </div>
        </div>
        <hr class="panel-wide"/>
        @include('articles::article.partials.meta')
    </div>

    @include('articles::article.partials.tags', ['tags' => $article->tagsArray])
</div>
@empty
<div class="panel">
    <div class="panel-body">
        <h2 class="m-t-none">@lang('article.messages.not_found')</h2>
    </div>
</div>
@endforelse
