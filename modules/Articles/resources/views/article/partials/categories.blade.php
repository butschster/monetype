@if(count($article->categories) > 0)
    <small class="categories-container">
        <i class="icon-suitcase category-icon"></i>
        @foreach($article->categories as $category)
            <span class="category-list">
                {!! link_to_route('category.show', $category->title, ['slug' => $category->slug]) !!}
            </span>
        @endforeach
    </small>
@endif