@extends('core::layout.main')

@section('content')
    <h2 class="page-header">{!! $pageTitle !!}</h2>

    <div class="panel">
        <table class="table table-striped">
            <colgroup>
                <col width="100px" />
                <col width="150px" />
                <col />
                <col width="150px" />
            </colgroup>
            <thead>
            <tr class="bg-primary">
                <th></th>
                <th>@lang('articles::check.field.created_at')</th>
                <th>@lang('articles::check.field.percent')</th>
                <th></th>
            </tr>
            </thead>
            @foreach($checks as $check)
                <tr>
                    <th class="text-center">{!! $check->status !!}</th>
                    <th>{!! $check->created !!}</th>
                    <td>{{ $check->percent }}%</td>
                    <td class="text-right">
                        {!! link_to_route('front.article.checks.details', '', [$check->article_id, $check->id], [
                            'class' => 'btn btn-xs btn-default', 'data-icon' => 'bar-chart'
                        ]) !!}
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    {!! $checks->render() !!}
@endsection