App.Form.extend('articles', {
	fieldsMeta: {
		title: 'string',
		text_source: 'markdown',
		forbid_comment: 'checkbox',
		tags: 'tags'
	},
	messages: {
		saved: 'Article saved'
	}
});

App.Controllers.add(['article.create', 'article.edit'], function(action) {
	App.Form.articles.init($('form'));
});

App.Controllers.add(['article.index', 'article.show'], function () {
	if(USER_ID) $('body').on('click', '.addToFavorite', addToFavorite);
});

function addToFavorite(e) {
    var $self = $(this);
    Api.post('/api.article.favorite', {id: $(this).data('id')}, function (response) {
		$self.parent().html(response.content);
    });
}