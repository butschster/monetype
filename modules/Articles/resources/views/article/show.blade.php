@extends('core::layout.main')

@section('content')
    <div class="articleItem articleItem--inner">
        <div class="articleItem--heading">
            @include('articles::article.partials.heading')
        </div>

        <h2 class="articleItem--title">{{ $article->title }}</h2>

        <div class="articleItem--content">
            @include('articles::article.partials.categories')
            @if(!$isPurchased)
                {!! $article->text_intro !!}

                @include('articles::article.partials.buy')
            @else
                {!! $article->text !!}
            @endif
        </div>

        @include('articles::tag.partials.tags', ['tags' => $tags])

        <div class="articleItem--meta">
            @include('articles::article.partials.meta', ['inner' => true])

            <div class="socials pull-right">
                <a href="#" class="rounded-icon social icon-vkontakte"><!-- vkontakte --></a>
                <a href="#" class="rounded-icon social icon-facebook"><!-- facebook --></a>
                <a href="#" class="rounded-icon social icon-twitter"><!-- twitter --></a>
                <a href="#" class="rounded-icon social icon-gplus"><!-- google plus --></a>
            </div>

            <div class="clearfix"></div>
        </div>

        @if(auth()->check() and $currentUser->can('edit', $article))
        <div class="articleItem--meta">
            <ul class="list-unstyled list-inline m-b-none">
                <li>
                    {!! link_to_route('front.article.money', trans('articles::article.button.purchases'), $article->id, [
                        'class' => 'btn btn-info', 'data-icon' => 'dollar'
                    ]) !!}
                </li>
                <li>
                    {!! link_to_route('front.article.edit', trans('articles::article.button.edit'), $article->id, [
                        'class' => 'btn btn-default', 'data-icon' => 'pencil'
                    ]) !!}
                </li>
            </ul>
        </div>
        @endif
    </div>
@endsection

@section('footer.content')
@if($article->isCommentsEnabled())
    @include('comments::list')
@endif
</div>
@endsection