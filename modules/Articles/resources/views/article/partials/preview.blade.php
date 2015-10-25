@if(!empty($textIntro))
    {!! $textIntro !!}

    @if(!empty($readMoreText))
    <a href="#" class="btn btn-default articleItem--readMoreButton">{{ $readMoreText }}</a>
    @endif

    <hr id="cut" class="pageBrake" />
@endif

{!! $text !!}