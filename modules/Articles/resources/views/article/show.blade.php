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

        @include('articles::article.partials.tags', ['tags' => $tags])

        <div class="articleItem--meta">
            @include('articles::article.partials.meta', ['inner' => true])

            <div class="socials pull-right">
                <a href="#" class="rounded-icon social fa fa-vk"><!-- vkontakte --></a>
                <a href="#" class="rounded-icon social fa fa-facebook"><!-- facebook --></a>
                <a href="#" class="rounded-icon social fa fa-twitter"><!-- twitter --></a>
                <a href="#" class="rounded-icon social fa fa-google-plus"><!-- google plus --></a>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>
@endsection

@section('footer.content')

    @include('comments::list')
</div>
@endsection