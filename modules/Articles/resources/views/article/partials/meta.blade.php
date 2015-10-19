<ul class="articleItem--meta-items list-unstyled list-inline">
    <li>
        <i class="fa fa-lg fa-fw fa-eye"></i> {{ $article->count_payments }}
    </li>
    <li>
        @lang('articles::article.label.cost', ['amount' => $article->cost])
    </li>

    <li>
        @include('articles::article.partials.favorites')
    </li>

    <li>
        @if($article->hasComments())
        <i class="fa fa-lg fa-fw fa-comment"></i> {{ $article->count_comments }}
        @else
            <i class="fa fa-lg fa-fw fa-comment-o"></i> 0
        @endif
    </li>

    @if($article->amount > 0)
    <li>
        {!! trans('articles::article.label.balance', ['amount' => link_to_route('front.article.money', $article->amount, $article->id)]) !!}
    </li>
    @endif
</ul>