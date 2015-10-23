@if($tags)
    @if(isset($showTagsTitle))
    <div class="headline headline-sm">
        <h5>@lang('articles::article.field.tags_list')</h5>
    </div>
    @endif
    <ul class="blogTags list-inline m-l-none">
        @foreach($tags as $tag)
        <li>{!! link_to_route('front.articles.byTag', $tag->name, ['tag' => $tag->name], ['class' => 'label']) !!}</li>
        @endforeach
    </ul>
@endif