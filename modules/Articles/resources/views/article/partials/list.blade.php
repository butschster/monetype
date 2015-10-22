@if($articles->count() > 0)
<div class="articleList">
    @foreach($articles as $article)
    <div class="articleItem">
        <div class="articleItem--heading">
            @include('articles::article.partials.heading')
        </div>

        <h3 class="articleItem--title">
            {!! link_to_route('front.article.show', $article->title, ['id' => $article->id]) !!}
        </h3>
        <div class="articleItem--content">
            {!! $article->text_intro !!}
        </div>

        @include('articles::tag.partials.tags', ['tags' => $article->tags])

        <div class="articleItem--meta">
            @include('articles::article.partials.meta')
        </div>
    </div>
    @endforeach
</div>
{!! $articles->render() !!}
@else
<div class="panel">
    <div class="panel-body">
        <h2 class="m-t-none">@lang('articles::article.message.empty_list')</h2>
    </div>
</div>
@endif
