@extends('core::layout.main')

@section('header.content')
<script>
   $(function() {
      $("#profileBackground").dropzone({
         url: "/api.profile.background",
         parallelUploads: 1,
         uploadMultiple: false,
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         complete: function(file) {
            var response = $.parseJSON(file.xhr.response);
            if(response.content) {
               this.removeFile(file);
               $('#profileBackground').backstretch(response.content);
            }
         }
      });

      $('#profileBackground').backstretch("{{ $user->getBackground() }}");
   })
</script>
<div class="m-t-n-md dropbox" id="profileBackground"></div>
<div class="panel">
   <div class="container text-center">
      <div class="panel-body">
         {!! $user->getAvatar(70) !!}
         <h3>@lang('users::user.title.profile', ['user' => $user->getName()])</h3>
      </div>
   </div>
</div>
@endsection