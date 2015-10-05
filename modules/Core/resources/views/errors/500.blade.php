@extends('core::layout.empty')

@section('content')
    <div class="error-container text-center">
        <h1>{{ $code }}</h1>
        <h3>@lang('error.title.500')</h3>

        @if(config('app.debug'))
            <div class="error-text">
                <span class="message">{{ $message }}
            </div>
        @endif
    </div>
@endsection