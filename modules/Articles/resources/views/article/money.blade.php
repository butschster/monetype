@extends('core::layout.main')

@section('content')
    <h2>@lang('articles::article.title.money', ['article' => $article->title])</h2>

    <div class="panel">
        <div class="stat-panel m-b-none">
            <div class="stat-cell bg-success padding-sm">
                <h4 class="m-t-xs">@lang('articles::article.label.total_amount')</h4>
                <h2 class="m-b-none m-t-none">{{ $article->amount }} <small><i class="fa fa-rub"></i></small></h2>
                <i class="fa fa-money bg-icon"></i>
            </div>
            <div class="stat-cell bg-danger padding-sm">
                <h4 class="m-t-xs">@lang('articles::article.label.count_payments')</h4>
                <h2 class="m-b-none m-t-none">{{ $article->count_payments }}</h2>
                <i class="fa fa-trophy bg-icon"></i>
            </div>
        </div>
    </div>
    @include('transactions::list')
@endsection