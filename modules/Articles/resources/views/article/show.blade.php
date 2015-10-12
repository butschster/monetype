@extends('core::layout.main')

@section('content')
    <div class="panel">

        <div class="panel-heading">
            @include('articles::article.partials.heading')
        </div>
        <div class="panel-body">
            <h3 class="m-t-none m-b-none">{{ $article->title }}</h3>
            @include('articles::article.partials.categories')
            <hr class="panel-wide" />
            @if(!$isPurchased)
                {!! $article->text_intro !!}
                <p>...</p>
                <h3 class="alert alert-warning">@lang('articles::article.message.not_enough_money')</h3>
            @else
                {!! $article->text !!}
            @endif

            <hr class="panel-wide" />

            @include('articles::article.partials.meta')

            <div class="socials pull-right">
                <a href="#" class="rounded-icon social fa fa-facebook"><!-- facebook --></a>
                <a href="#" class="rounded-icon social fa fa-twitter"><!-- twitter --></a>
                <a href="#" class="rounded-icon social fa fa-google-plus"><!-- google plus --></a>
                <a href="#" class="rounded-icon social fa fa-pinterest"><!-- pinterest --></a>
                <a href="#" class="rounded-icon social fa fa-linkedin"><!-- linkedin --></a>
            </div>
        </div>

        @include('articles::article.partials.tags', ['tags' => $tags, 'showTagsTitle' => true])
    </div>
@endsection