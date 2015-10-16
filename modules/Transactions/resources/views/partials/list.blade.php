<div class="panel">
    <table class="table table-striped">
        <colgroup>
            <col />
            <col width="100px" />
            <col width="150px" />
        </colgroup>
        <thead>
            <tr class="bg-primary">
                <th>@lang('transactions::transaction.field.debit')</th>
                <th class="text-right">@lang('transactions::transaction.field.amount')</th>
                <th class="text-right">@lang('transactions::transaction.field.created_at')</th>
            </tr>
        </thead>
        @foreach($transactions as $transaction)
            <tr>
                <th>{!! $transaction->debitAccount->getAvatar(20) !!} {!! $transaction->debitAccount->getProfileLink() !!}</th>
                <td class="text-right">{{ $transaction->amount }} р.</td>
                <td class="text-right">{{ $transaction->created }}</td>
            </tr>
        @endforeach
    </table>
</div>

@if($transactions instanceof \Illuminate\Contracts\Pagination\Paginator )
{!! $transactions->render() !!}
@endif