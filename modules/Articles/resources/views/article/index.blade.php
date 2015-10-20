@extends('core::layout.main')

@section('content')
    <div class="row">
        <div class="col-md-12">
            фильтры
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <h2>@lang('articles::article.title.list')</h2>
            @include('articles::article.partials.list')
        </div>
        <div class="col-md-4">
            <div>top tags</div>
            <div>top article</div>
            <div>top users</div>
            <footer>
                <ul>
                    <li>О проекте</li>
                    <li>Пользовательское соглашение</li>
                    <li>Правила</li>
                    <li>Услуги</li>
                    <li>Авторам</li>
                    <li>Контакты</li>
                </ul>
                <p class="copy">©&nbsp;MoneType,2015</p>
            </footer>
        </div>
    </div>
@endsection