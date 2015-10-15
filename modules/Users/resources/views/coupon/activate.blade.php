<h3>@lang('users::coupon.title.activate')</h3>

{!! Form::open([
    'route' => 'front.coupon.activate',
    'class' => 'panel form-horizontal',
]) !!}

    <div class="panel-body">
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">@lang('users::coupon.field.code')</label>
            <div class="col-sm-10">
                {!! Form::text('code', null, ['class' => 'form-control', 'id' => 'inputCode']) !!}
            </div>
        </div>
    </div>

    <div class="panel-footer">
        <div class="form-group m-b-none">
            <div class="col-sm-offset-2 col-sm-10">
                {!! Form::button(trans('users::coupon.button.activate'), [
                    'type' => 'submit', 'data-icon' => 'check',
                    'class' => 'btn btn-info btn-lg'
                ]) !!}
            </div>
        </div>
    </div>

{!! Form::close() !!}