@extends('core::layout.main')

@section('content')
    @include('users::coupon.activate')
    @include('users::coupon.create')

    @include('users::coupon.list')
@endsection