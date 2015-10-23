<ul class="articleItem--meta-items list-unstyled list-inline">
    <li>
        <div class="articleItem--meta-coost">
        @if(!$article->isFree())
            @lang('articles::article.label.cost', ['amount' => $article->getFormatedCost()])

            @if($article->isPaysStatisticsEnabled())
            &nbsp;&nbsp;
            {!! trans('articles::article.label.balance', ['amount' => $article->amount]) !!}
            @endif
        @else
            @lang('articles::article.label.free')
        @endif
        </div>
    </li>

    @if($article->isViewsStatisticsEnabled())
    <li>
        <i class="icon-eye"></i> {{ $article->count_payments }}
    </li>
    @endif

    @if(empty($inner) and $article->isCommentsEnabled())
        <li>
            @if($article->hasComments())
                <i class="icon-comment"></i> {!! link_to_route('front.article.comments', $article->count_comments, $article->id) !!}
            @else
                <i class="icon-comment-o"></i> 0
            @endif
        </li>
    @endif
</ul>