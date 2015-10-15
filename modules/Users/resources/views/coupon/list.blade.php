@if($coupons->count() > 0)
<h3>@lang('users::coupon.title.list')</h3>

<div class="panel">
    <table class="table">
        <colgroup>
            <col />
            <col width="100px" />
            <col width="150px" />
            <col width="50px" />
        </colgroup>
        <thead>
            <tr class="bg-primary">
                <th>@lang('users::coupon.field.code')</th>
                <th class="text-right">@lang('users::coupon.field.amount')</th>
                <th class="text-right">@lang('users::coupon.field.expired_at')</th>
                <th></th>
            </tr>
        </thead>
        @foreach($coupons as $coupon)
            <tr @if($coupon->isExpired()) class="bg-danger" @endif>
                <th>{{ $coupon->code }}</th>
                <td class="text-right">{{ $coupon->formatedAmount }} р.</td>
                <td class="text-right">{{ $coupon->expired }}</td>
                <td class="text-center">
                    {!! Form::open(['route' => ['front.coupon.delete', $coupon->id], 'method' => 'delete']) !!}
                    {!! Form::button('', [
                        'type' => 'submit', 'data-icon' => 'trash',
                        'class' => 'btn btn-xs btn-danger'
                     ]) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
    </table>
</div>

{!! $coupons->render() !!}
@endif