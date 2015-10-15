@extends('core::layout.empty')

@section('content')
    <div class="error-container text-center">
        <h1>{{ $code }}</h1>
        <h3>{{ $message }}</h3>
    </div>
@endsection