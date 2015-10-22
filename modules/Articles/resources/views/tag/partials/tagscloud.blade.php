<div class="well tagsCloud">
    <div class="headline">
        <h4>@lang('articles::tag.title.featured_tags')</h4>
    </div>

    <ul class="list-inline m-l-none">
        @foreach($tagsCloud as $tag)
            <li>{!! $tag !!}</li>
        @endforeach
    </ul>
</div>