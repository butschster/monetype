@if($tags)
    @if(isset($showTagsTitle))
        <div class="headline headline-sm">
            <h5>@lang('article.field.tags')</h5>
        </div>
    @endif
    <ul class="blog-tags list-unstyled list-inline">
        @foreach($tags as $tag)
            <li>{!! link_to_route('front.articles.byTag', $tag, ['tag' => $tag], ['class' => 'label']) !!}</li>
        @endforeach
    </ul>
@endif