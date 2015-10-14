<ul class="list-unstyled m-t-xs list-inline pull-left m-b-none m-t-none">
    <li>
        <i class="fa fa-fw fa-eye"></i> {{ $article->count_payments }}
    </li>
    <li>
        @lang('articles::article.label.cost', ['amount' => $article->cost])
    </li>
    <li>
        {!! link_to_route('front.article.money', trans('articles::article.label.balance', ['amount' => $article->amount]), $article->id) !!}
    </li>
</ul>