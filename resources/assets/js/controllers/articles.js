App.Form.extend('articles', {
	fieldsMeta: {
		title: 'string',
		text_source: 'markdown',
		disable_comments: 'checkbox',
		disable_stat_views: 'checkbox',
		disable_stat_pays: 'checkbox',
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

App.Controllers.add('article.show', function () {
	$('.commentItem--reply').on('click', function(e) {
		e.preventDefault();
		showCommentForm($(this), $(this).data('id'));
	});

	$('.commentForm--title a').on('click', function(e) {
		e.preventDefault();
		showCommentForm($(this).parent(), null);
	});

	// Validation for login form
	$("#commentForm").validate({
		rules: {
			text: {
				required: true,
				minlength: 10,
			},
			title: {
				maxlength: 255
			}
		}
	});
});

function showCommentForm($container, parentId) {
	$('#commentParentId').val(parentId);
	$('#commentForm').insertAfter($container);
}

function addToFavorite(e) {
    var $self = $(this);
    Api.post('/api.article.favorite', {id: $(this).data('id')}, function (response) {
		$self.parent().html(response.content);
    });
}