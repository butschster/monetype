App.Form.extend('articles', {
	fields: {
		id: 'integer',
		title: 'string',
		text_intro: 'textarea',
		text: 'ckeditor',
		forbid_comment: 'checkbox',
		tagsList: 'tags',
		categories_list: 'multiple'
	},
	messages: {
		saved: 'Article saved'
	},
	onSubmit: function(e) {
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
	}
});

App.Controllers.add(['articles.create', 'articles.edit'], function(action) {
	App.Form.articles.init($('form'));
});