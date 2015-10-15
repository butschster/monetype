<h3>@lang('users::coupon.title.create')</h3>

{!! Form::open([
    'route' => 'front.coupon.create',
    'class' => 'panel form-horizontal',
]) !!}

    <div class="panel-body">
        <div class="form-group form-group-lg form-inline">
            <label class="col-sm-2 control-label required">@lang('users::coupon.field.amount')</label>
            <div class="col-sm-10">
                {!! Form::input('number', 'amount', null, [
                    'class' => 'form-control', 'id' => 'inputAmount',
                    'min' => 1, 'max' => 100, 'maxlength' => 6, 'size' => 6
                ]) !!}
            </div>
        </div>

       <div class="form-group form-inline">
            <label class="col-sm-2 control-label">@lang('users::coupon.field.expired_at')</label>
            <div class="col-sm-10">
                {!! Form::text('expired_at', null, [
                    'class' => 'form-control input-date', 'id' => 'inputExpiredAt'
                ]) !!}
            </div>
        </div>
    </div>

    <div class="panel-footer">
        <div class="form-group m-b-none">
            <div class="col-sm-offset-2 col-sm-10">
                {!! Form::button(trans('users::coupon.button.create'), [
                    'type' => 'submit', 'data-icon' => 'plus',
                    'class' => 'btn btn-success btn-lg'
                ]) !!}
            </div>
        </div>
    </div>

{!! Form::close() !!}