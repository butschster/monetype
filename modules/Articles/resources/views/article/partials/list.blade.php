@if($articles->count() > 0)
<div class="articleList">
    @foreach($articles as $article)
    <div class="articleItem">
        <div class="articleItem--heading">
            @include('articles::article.partials.heading')
        </div>

        <h3 class="articleItem--title">
            {!! link_to($article->getRouteLink(), $article->title) !!}
        </h3>
        <div class="articleItem--content">
            {!! $article->text_intro !!}

            @if($article->hasReadModerButton())
            {!! link_to($article->getRouteLink(), $article->read_more_text, ['class' => 'articleItem--readMoreButton']) !!}
            @endif
        </div>

        @include('articles::tag.partials.tags', ['tags' => $article->tags])

        <div class="articleItem--meta">
            @include('articles::article.partials.meta')
        </div>
    </div>
    @endforeach
</div>
@if($articles instanceof \Illuminate\Contracts\Pagination\Paginator)
{!! $articles->render() !!}
@endif
@else
<div class="alert alert-info m-t-lg">
    <?php if(empty($emptyMessage)) $emptyMessage = trans('articles::article.message.empty_list'); ?>
    <h4 class="m-b-none m-t-none">{!! $emptyMessage !!}</h4>
</div>
@endif
