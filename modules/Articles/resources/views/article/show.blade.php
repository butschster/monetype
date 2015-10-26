@extends('core::layout.main')

@section('content')
    <div class="articleItem articleItem--inner">
        <div class="articleItem--heading">
            @include('articles::article.partials.heading')
        </div>

        <h2 class="articleItem--title page-header">{{ $article->title }}</h2>

        <div class="articleItem--content">
            @include('articles::article.partials.categories')
            @if(!$isPurchased)
                {!! $article->text_intro !!}

                @include('articles::article.partials.buy')
            @else
                @if(!empty($article->text_intro))
                {!! $article->text_intro !!}
                <hr id="cut" class="pageBrake" />
                @endif

                {!! $article->text !!}
            @endif
        </div>

        @include('articles::tag.partials.tags', ['tags' => $tags])

        <div class="articleItem--meta">
            @include('articles::article.partials.meta', ['inner' => true])

            <div class="pull-right">
                @include('core::layout.partials.social')
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
                    {!! link_to_route('front.article.checks', trans('articles::article.button.checks'), $article->id, [
                        'class' => 'btn btn-warning', 'data-icon' => 'shield'
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