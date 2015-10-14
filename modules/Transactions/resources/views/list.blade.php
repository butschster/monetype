<div class="panel">
    <table class="table">
        <colgroup>
            <col />
            <col width="100px" />
            <col width="150px" />
        </colgroup>
        <thead>
        <tr>
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
        <tfoot>
            <tr>
                <th colspan="2" class="text-right">
                    @lang('transactions::transaction.label.total')
                    {{ \Modules\Support\Helpers\String::formatAmount($transactions->sum('amount')) }} р.
                </th>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

@if($transactions instanceof \Illuminate\Contracts\Pagination\Paginator )
{!! $transactions->render() !!}
@endif