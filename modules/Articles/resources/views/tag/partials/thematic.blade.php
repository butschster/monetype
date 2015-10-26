@foreach($tags as $tag)
    <div class="tagsCloud--tag tag-size-medium" data-id="{{ $tag->id }}">
        {{ $tag->name }} <small>({{ $tag->count }})</small>
        <span class="close">&times;</span>
    </div>
@endforeach