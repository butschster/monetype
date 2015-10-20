@extends('core::layout.main')

@section('content')
    <h2>@lang('articles::article.title.list')</h2>

    <div class="row">
        <div class="col-sm-7">
            @include('articles::article.partials.list')
        </div>
        <div class="col-sm-5">

        </div>
    </div>

@endsection