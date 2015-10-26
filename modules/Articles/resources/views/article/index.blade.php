@extends('core::layout.main')

@section('header.content')
    <div class="container">
        <h2 class="page-header">{!! $pageTitle !!}</h2>
    </div>

    @include('articles::article.partials.subnav')
@endsection

@section('content')
    <?php if(!isset($emptyMessage)) $emptyMessage = null; ?>

    <div class="row">
        <div class="col-sm-7">
            @include('articles::article.partials.list', ['emptyMessage' => $emptyMessage])
        </div>
        <div class="col-sm-5">
            @include('articles::tag.partials.tagscloud')
        </div>
    </div>
@endsection