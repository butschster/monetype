<ul class="articleItem--meta-items list-unstyled list-inline">
    <li>
        <div class="articleItem--meta-coost">
            @lang('articles::article.label.cost', ['amount' => $article->cost])

            @if($article->amount > 0)
            &nbsp;&nbsp;
            {!! trans('articles::article.label.balance', ['amount' => link_to_route('front.article.money', $article->amount, $article->id)]) !!}
            @endif
        </div>
    </li>

    <li>
        @include('articles::article.partials.favorites')
    </li>

    <li>
        <i class="fa fa-fw fa-eye"></i> {{ $article->count_payments }}
    </li>

    @if(empty($inner))
        <li>
            @if($article->hasComments())
                <i class="fa fa-fw fa-comment"></i> {!! link_to_route('front.article.comments', $article->count_comments, $article->id) !!}
            @else
                <i class="fa fa-fw fa-comment-o"></i> 0
            @endif
        </li>
    @endif
</ul>