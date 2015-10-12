@extends('core::layout.empty')

@section('content')
    <div class="error-container text-center">
        <h1>{{ $code }}</h1>

        <h3>
        @if(!empty($message))
            {{ $message }}
        @else
            {{ trans('core::core.message.something_went_wrong') }}
        @endif
        </h3>
    </div>
@endsection