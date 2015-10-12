<ul class="list-unstyled m-t-xs list-inline pull-left m-b-none m-t-none">
    <li>
        <i class="fa fa-fw fa-eye"></i> {{ $article->count_payments }}
    </li>
    <li>
        @lang('articles::article.label.cost', ['amount' => $article->cost])
    </li>
    <li>
        @lang('articles::article.label.balance', ['amount' => $article->amount])
    </li>
</ul>