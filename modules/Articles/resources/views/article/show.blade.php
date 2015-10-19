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
                <p>...</p>
                <h3 class="alert alert-warning">@lang('articles::article.message.not_enough_money')</h3>
            @else
                {!! $article->text !!}
            @endif
        </div>

        @include('articles::article.partials.tags', ['tags' => $tags])

        <hr />

        <div class="articleItem--meta  shadow1">
            @include('articles::article.partials.meta')

            <div class="socials pull-right">
                <a href="#" class="rounded-icon social fa fa-facebook"><!-- facebook --></a>
                <a href="#" class="rounded-icon social fa fa-twitter"><!-- twitter --></a>
                <a href="#" class="rounded-icon social fa fa-google-plus"><!-- google plus --></a>
                <a href="#" class="rounded-icon social fa fa-pinterest"><!-- pinterest --></a>
                <a href="#" class="rounded-icon social fa fa-linkedin"><!-- linkedin --></a>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>
@endsection

@section('footer.content')

    @include('comments::list')
</div>
@endsection