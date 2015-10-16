@extends('core::layout.main')

@section('content')
    <h2>{{ $article->title }}</h2>

    <div class="panel">
        <div class="panel-heading">
            @include('articles::article.partials.heading')
        </div>
        <div class="panel-body">
            @include('articles::article.partials.categories')
            @if(!$isPurchased)
                {!! $article->text_intro !!}
                <p>...</p>
                <h3 class="alert alert-warning">@lang('articles::article.message.not_enough_money')</h3>
            @else
                {!! $article->text !!}
            @endif

            <hr class="panel-wide" />

            @include('articles::article.partials.tags', ['tags' => $tags, 'showTagsTitle' => true])
        </div>
        <div class="panel-footer clearfix">
            @include('articles::article.partials.meta')

            <div class="socials pull-right">
                <a href="#" class="rounded-icon social fa fa-facebook"><!-- facebook --></a>
                <a href="#" class="rounded-icon social fa fa-twitter"><!-- twitter --></a>
                <a href="#" class="rounded-icon social fa fa-google-plus"><!-- google plus --></a>
                <a href="#" class="rounded-icon social fa fa-pinterest"><!-- pinterest --></a>
                <a href="#" class="rounded-icon social fa fa-linkedin"><!-- linkedin --></a>
            </div>
        </div>
    </div>

    @include('comments::list')
@endsection