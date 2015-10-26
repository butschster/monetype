@extends('core::layout.main')

@section('header.content')
<div class="well m-t-n-md">
    <div class="container">
        <h2 class="page-header">{!! $pageTitle !!}</h2>

        <div class="panel">
            <div class="stat-panel m-b-none">
                <div class="stat-cell bg-success padding-sm">
                    <h4 class="m-t-xs">@lang('articles::article.label.total_amount')</h4>
                    <h2 class="m-b-none m-t-none">{{ $article->amount }} <small><i class="icon-rouble"></i></small></h2>
                    <i class="icon-dollar bg-icon"></i>
                </div>
                <div class="stat-cell bg-danger padding-sm">
                    <h4 class="m-t-xs">@lang('articles::article.label.count_payments')</h4>
                    <h2 class="m-b-none m-t-none">{{ $article->count_payments }}</h2>
                    <i class="icon-award bg-icon"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
    <div class="well">
        @include('transactions::partials.list')
    </div>
@endsection