@extends('core::layout.main')

@section('content')
   <h2>@lang('users::user.title.profile', ['user' => $user->getName()])</h2>

   <div class="panel">

      <div class="panel-body">

      </div>
   </div>
@endsection