<div class="navbar navbar-subnav">
    <div class="container">
        <ul class="nav navbar-nav">
            <li @if(Request::is('all')) class="active" @endif >{!! link_to_route('front.articles.index', trans('articles::article.menu.index')) !!}</li>
            <li @if(Request::is('top')) class="active" @endif>{!! link_to_route('front.articles.top', trans('articles::article.menu.top')) !!}</li>

            @if(auth()->check())
            <li @if(Request::is('thematic')) class="active" @endif>{!! link_to_route('front.articles.thematic', trans('articles::article.menu.thematic')) !!}</li>
            @endif
        </ul>
    </div>
</div>