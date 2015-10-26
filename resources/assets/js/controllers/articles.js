App.Form.extend('articles', {
	fieldsMeta: {
		title: 'string',
		text_source: 'markdown',
		disable_comments: 'checkbox',
		disable_stat_views: 'checkbox',
		disable_stat_pays: 'checkbox',
		tags_list: 'tags',
		cost: 'rangeslider'
	},
	onSubmit: function(e) {
		e.preventDefault();
		this.clearErrors();

		var action = this._submitButton.val();

		var url = this._api_url;
		var method = this._api_method;
		switch (action) {
			case 'publish':
				url = '/api.article.publish/' + this._id;
				method = 'post';
				break;
			case 'draft':
				url = '/api.article.draft/' + this._id;
				method = 'post';
				break;
		}

		$(':button', this._form).prop('disabled', true);
		Api[method](url, this.getFieldsData(), $.proxy(this.onResponse, this));
	}
});

App.Controllers.add(['article.create', 'article.edit'], function(action) {
	App.Form.articles.init($('#articleForm'));
	App.Form.articles._id = ARTICLE_ID;
});

App.Controllers.add('article.list.thematic', function(action) {
	$('#addTagInput').typeahead({
		afterSelect: function (val) {
			var $self = this.$element;

			addTagToThematic(val, function(response) {
				$self.val("");
				$('#thematicTags').html(response.content);

				Api.get('/api.articles.thematic', {}, function(response) {
					$('#thematicArticles').html(response.content);
				});
			});
		},
		source: function(query, callback) {
			return $.get('/api.tags.search', {query: query}, function(response) {
				callback(response)
			});
		}
	});

	$('#thematicTags').on('click', '.close', function() {
		var id = $(this).closest('.tagsCloud--tag').data('id');
		Api.delete('/api.tags.thematic', {tag: id}, function(response) {
			$('#thematicTags').html(response.content);
			Api.get('/api.articles.thematic', {}, function(response) {
				$('#thematicArticles').html(response.content);
			});
		});
	});
});

$(function() {
	$('body').on('click', '.addToFavorite', addToFavorite);
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

function addTagToThematic(tag, callback) {
	Api.post('/api.tags.thematic', {tag: tag}, function(response) {
		if (typeof callback === 'function') {
			callback(response)
		}
	});
}

function addToFavorite(e) {
	var $self = $(this);

	Api.post('/api.article.favorite', {id: $self.data('id')}, function (response) {
		$self.closest('.articleItem--favorites').replaceWith(
			$(response.content).find('.icon-bookmark').addClass('animated bounceIn').end()
		);
	});
}