@extends('core::layout.main')

@section('content')
    <h2 class="page-header">@lang('users::coupon.title.coupons')</h2>

    @include('users::coupon.activate')
    @include('users::coupon.create')

    @include('users::coupon.list')
@endsection