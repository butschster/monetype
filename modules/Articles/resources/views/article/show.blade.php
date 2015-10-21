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

        @if(auth()->check() and $currentUser->can('edit', $article))
        <div class="articleItem--meta">
            <ul class="list-unstyled list-inline m-b-none">
                <li>
                    {!! link_to_route('front.article.preview', trans('articles::article.button.preview'), $article->id, [
                        'class' => 'btn btn-default', 'data-icon' => 'pencil'
                    ]) !!}
                </li>
                <li>
                    {!! link_to_route('front.article.money', trans('articles::article.button.purchases'), $article->id, [
                        'class' => 'btn btn-info', 'data-icon' => 'money'
                    ]) !!}
                </li>

                @if(!$article->isDrafted())
                <li class="pull-right">
                    {!! Form::open(['route' => ['front.article.draft', $article->id], 'method' => 'put', 'class' => 'm-b-none']) !!}
                    {!! Form::button(trans('articles::article.button.draft'), [
                        'type' => 'submit',
                        'class' => 'btn btn-danger', 'data-icon' => 'eye-slash'
                    ]) !!}
                    {!! Form::close() !!}
                </li>
                @endif
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