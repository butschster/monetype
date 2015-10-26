@extends('core::layout.main')

@section('content')
    <h2 class="page-header">{!! $pageTitle !!}</h2>

    @if(!$articles->count())
        <div class="alert alert-info m-t-lg">
            <?php if(empty($emptyMessage)) $emptyMessage = trans('articles::article.message.no_search_results'); ?>
            <h4 class="m-b-none m-t-none">{!! $emptyMessage !!}</h4>
        </div>
    @else
    <div class="row">
        <div class="col-sm-7">
            @include('articles::article.partials.list')
        </div>
        <div class="col-sm-5">
            @include('articles::tag.partials.tagscloud')
        </div>
    </div>
    @endif
@endsection