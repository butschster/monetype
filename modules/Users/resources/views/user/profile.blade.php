@extends('core::layout.main')

@section('header.content')
@if($user->hasBackground())
<script>
   $(function() {
      $('#profileBackground').backstretch('{{ $user->getBackground() }}');
   })
</script>

<div class="well well-lg m-t-n-md" id="profileBackground" style="height: 300px;"></div>
@endif
@endsection

@section('content')
   <div class="panel">
      <div class="panel-body">
         {!! $user->getAvatar(70) !!}
         <h3>@lang('users::user.title.profile', ['user' => $user->getName()])</h3>
      </div>
   </div>
@endsection