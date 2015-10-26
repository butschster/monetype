@extends('core::layout.main')

@section('header.content')
@if($user->hasBackground())
<script>
   $(function() {
      $('#profileBackground').backstretch('{{ $user->getBackground() }}');
   })
</script>

<div class="m-t-n-md" id="profileBackground"></div>
@endif
<div class="panel">
   <div class="container text-center">
      <div class="panel-body">
         {!! $user->getAvatar(70) !!}
         <h3>{!! $pageTitle !!}</h3>
      </div>
   </div>
</div>
@endsection