@extends('core::layout.main')

@section('content')
    <h2>@lang('articles::check.title.index')</h2>

    <div class="panel">
        <table class="table table-striped">
            <colgroup>
                <col width="150px" />
                <col />
            </colgroup>
            <thead>
            <tr class="bg-primary">
                <th>@lang('articles::check.field.created_at')</th>
                <th>@lang('articles::check.field.percent')</th>
            </tr>
            </thead>
            @foreach($checks as $check)
                <tr>
                    <th>{!! $check->created !!}</th>
                    <td>{{ $check->percent }}%</td>
                </tr>
            @endforeach
        </table>
    </div>

    {!! $checks->render() !!}
@endsection