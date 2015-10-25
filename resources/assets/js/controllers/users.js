App.Controllers.add(['user.bookmarks'], function() {
    $('#searchBookmarked').on('keyup', function() {
       Api.get('/api.filter.bookmarks', {query: $(this).val()}, function(response) {
           if(response.content) {
               $('.articleList').html(response.content);
           }
       })
    });
});