@extends('core::layout.main')

@section('content')
    <h2 class="page-header">{!! $pageTitle !!}</h2>

    <script>
        $(function() {
            var hightlight = {{ json_encode($check->response['highlight']) }};

            function highlight_words(text, hightlight)
            {
                var words = text.split(" ");
                for (var i = 0; i < hightlight.length; i++)
                {
                    words[ hightlight[i][0] ] = '<strong>' + words[ hightlight[i] ];
                    words[ hightlight[i][1] ] = words[ hightlight[i] ] + '</strong>';
                }

                return words.join(" ");
            }

            $('.panel-body').html(highlight_words($('.panel-body').html(), hightlight));
        });
    </script>
    <div class="panel">
        <div class="panel-body">
            {{ $check->text  }}
        </div>
    </div>
@endsection