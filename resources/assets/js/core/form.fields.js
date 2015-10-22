App.Form.Field = {
    extend: function (key, data) {
        if (!data) var data = {};

        data['_prefix'] = key;
        App.Form.Field[key] = $.extend({}, this._decorator, data);
        return App.Form.Field[key];
    },
    _decorator: {
        _form: null,
        _name: null,
        _element: null,
        init: function (form, name) {
            this._form = form;
            this._name = name;
            this._element = this.getFieldInput();

            this._init();

            return this;
        },
        getFieldInput: function () {
            return $(':input[name="' + this.getName() + '"]', this._form._form);
        },
        getElement: function () {
            return this._element;
        },
        getValue: function () {
            return this.getElement().val();
        },
        setValue: function (value) {
            this.getElement().val(value);
        },
        getName: function () {
            return this._name;
        },
        _init: function() {

        }
    }
};

App.Form.Field.extend('default');

App.Form.Field.extend('checkbox', {
    getFieldInput: function() {
        return $(':input[name="' + this.getName() + '"]:not(:hidden)', this._form._form);
    },
    getValue: function() {
        return this.getElement().prop('checked');
    },
    setValue: function(value) {
        return this.getElement().prop('checked', value).trigger('change');
    }
});

App.Form.Field.extend('markdown', {
    _init: function() {
        var $elm = this.getElement();
        this.editor = new SimpleMDE({
            element: $elm[0],
            spellChecker: false
        });
    },
    getValue: function() {
        return this.editor.value();
    },
    setValue: function(value) {
        return this.editor.value(value);
    }
});

App.Form.Field.extend('tags', {
    _init: function() {
        this.getElement().tagsinput({
            minLength: 2,
            confirmKeys: [13, 44],
            trimValue: true,
            freeInput: true,
            typeahead: {
                afterSelect: function(val) { this.$element.val(""); },
                source: function(query) {
                    return $.get('/api.tags.search', {query: query});
                }
            }
        });
    },
    setValue: function(value) {
        return this.getElement().tagsinput('add', value);
    }
});



App.Form.Field.extend('rangeslider', {
    _init: function() {
        this.slider = $('<div />').insertBefore(this.getElement())[0];

        var self = this;

        noUiSlider.create(this.slider, {
            start: this.getElement().val() || 0, // Handle start position
            step: this.getElement().data('step') || 1, // Slider moves in increments of '10',
            range: this.getElement().data('range') || {min: [0], max: 100}
        });

        this.slider.noUiSlider.on('update', function(values, handle){
            $('#slider-' + self.getName() + ' .slider-value').text(values[handle]);
            self.getElement().val(values[handle]);
        });
    },
    getValue: function() {
        return this.slider.noUiSlider.get();
    },
    setValue: function(value) {
        return this.slider.noUiSlider.set(value);
    }
});