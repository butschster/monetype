App.Form.extend('articles', {
	fields: {
		title: 'string',
		text_source: 'textarea',
		forbid_comment: 'checkbox',
		tags: 'tags'
	},
	messages: {
		saved: 'Article saved'
	}
	/*onSubmit: function(e) {
		e.preventDefault();
		this.clearErrors();

		var action = this._submitButton.val();

		switch (action) {
			case 'publish':
				var url = '/api.articles.publish/' + this._id;
				break;
			default:
				var url = this._api_url;
		}

		$(':button', this._form).prop('disabled', true);
		Api[this._api_method](url, this.getFieldsData(), $.proxy(this.onResponse, this));
	}*/
});

App.Controllers.add(['article.create', 'article.edit'], function(action) {
	App.Form.articles.init($('form'));
});

App.Controllers.add(['article.index', 'article.show'], function () {
    $('body').on('click', '.addToFavorite', addToFavorite);
});

function addToFavorite(e) {
    var $self = $(this);
    Api.post('/api.article.favorite', {id: $(this).data('id')}, function (response) {
		$self.parent().html(response.content);
    });
}