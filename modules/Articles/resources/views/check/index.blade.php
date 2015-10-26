@extends('core::layout.main')

@section('content')
    <h2 class="page-header">{!! $pageTitle !!}</h2>

    <div class="panel">
        <table class="table table-striped">
            <colgroup>
                <col width="150px" />
                <col />
                <col width="150px" />
                <col width="150px" />
                <col width="50px" />
            </colgroup>
            <thead>
            <tr class="bg-primary">
                <th>@lang('articles::check.field.user_id')</th>
                <th>@lang('articles::check.field.article_id')</th>
                <th>@lang('articles::check.field.created_at')</th>
                <th>@lang('articles::check.field.percent')</th>
                <th></th>
            </tr>
            </thead>
            @foreach($checks as $check)
                <tr>
                    <th>{!! $check->user->getProfileLink() !!}</th>
                    <th>{!! link_to_route('front.article.show', $check->article->title, ['id' => $check->article->id]) !!} {!! $check->article->statusTitle !!}</th>
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