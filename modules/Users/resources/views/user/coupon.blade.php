@extends('core::layout.main')

@section('content')
    <h2>@lang('users::user.title.activate_coupon')</h2>

    {!! Form::open([
		'route' => 'front.user.activate_coupon.post',
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
                        'type' => 'submit',
                        'class' => 'btn btn-success btn-lg'
                    ]) !!}
                </div>
            </div>
        </div>
    {!! Form::close() !!}
@endsection