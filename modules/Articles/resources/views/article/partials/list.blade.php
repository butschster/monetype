@if($articles->count() > 0)
@foreach($articles as $article)
<div class="panel">
    <div class="panel-heading">
        @include('articles::article.partials.heading')
    </div>
    <div class="panel-body">
        <div class="media">
            <div class="media-body">
                <h3 class="media-heading">
                    {!! link_to_route('article.show', $article->title, ['id' => $article->id]) !!}
                </h3>

                <div class="media-content m-t-md">
                    {!! $article->text_intro !!}

                    @include('articles::article.partials.tags', ['tags' => $article->tagList])
                </div>
            </div>
        </div>
        <hr class="panel-wide m-t-sm m-b-sm" />
        @include('articles::article.partials.meta')
    </div>


</div>
@endforeach
{!! $articles->render() !!}
@else
<div class="panel">
    <div class="panel-body">
        <h2 class="m-t-none">@lang('articles::article.message.empty_list')</h2>
    </div>
</div>
@endif
