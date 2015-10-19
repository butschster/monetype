var App = {
    Controllers: {
        _controllers: [],
        add: function (rout, callback) {
            if (typeof(callback) != 'function')
                return this;

            if (typeof(rout) == 'object')
                for (var i = 0; i < rout.length; i++)
                    this._controllers.push([rout[i], callback]);
            else if (typeof(rout) == 'string')
                this._controllers.push([rout, callback]);

            return this;
        },
        call: function () {
            var body_id = $('body:first').attr('id');
            for (var i = 0; i < this._controllers.length; i++)
                if (body_id == 'body.' + this._controllers[i][0])
                    this._controllers[i][1](this._controllers[i][0]);
        }
    },
    Components: {

        // 0: name
        // 1: callback
        // 2: priority

        _elements: [],
        _modules: [],
        add: function (module, callback, priority) {
            if (typeof(callback) != 'function')
                return this;

            this._elements.push([module, callback, priority || 0]);
            return this;
        },
        addModule: function (module, callback, priority) {
            if (typeof(callback) != 'function')
                return this;

            this._modules.push([module, callback, priority || 0]);
            return this;
        },
        call: function (module) {
            for (var i = 0; i < this._elements.length; i++) {
                var elm = this._elements[i];
                if (_.isArray(module) && _.indexOf(module, elm[0]) != -1)
                    elm[1]();
                else if (module == elm[0])
                    elm[1]();
            }
        },
        init: function (module) {
            this._elements = _.sortBy(this._elements, 2);
            this._modules = _.sortBy(this._modules, 2);

            for (i in this._elements) {
                var elm = this._elements[i];

                try {
                    if (!module)
                        elm[1]();
                    else if (_.isArray(module) && _.indexOf(module, elm[0]) != -1)
                        elm[1]();
                    else if (module == elm[0])
                        elm[1]();
                } catch (e) {
                    console.log(elm[0], e);
                }
            }

            var modules = [];
            $('[data-module]').each(function () {
                modules.push($(this).data('module'));
            });

            modules = _.uniq(modules);
            for (i in this._modules) {
                var module = this._modules[i],
                    moduleName = module[0];

                if (_.indexOf(modules, moduleName) != -1) {
                    module[1]();
                }
            }
        }
    },
    Messages: {
        init: function () {
            if (typeof MESSAGE_ERRORS == 'undefined') return;
            this.parse(MESSAGE_ERRORS, 'error');
            this.parse(MESSAGE_SUCCESS);

            $('body').on('show_message', $.proxy(function () {
                var messages = _.toArray(arguments).slice(1);
                this.parse(messages);
            }, this));
        },
        parse: function ($messages, $type) {
            for (text in $messages) {
                if (text == '_external') {
                    this.parse($messages[text], $type);
                    continue;
                }

                this.show($messages[text], $type);
            }
        },
        show: function (msg, type, icon) {
            if (!type) type = 'success';

            window.top.noty({
                layout: 'topRight',
                type: type,
                icon: icon || 'fa fa-ok',
                text: decodeURIComponent(msg)
            });
        },
        error: function (message) {
            this.show(message, 'error');
        }
    },
    Dialog: {
        confirm: function (message, callback, title, className) {
            bootbox.confirm({
                title: title || 'Подтверждение действия',
                message: message,
                className: 'modal-alert modal-warning',
                closeButton: false,
                callback: function (result) {
                    if (result) callback();
                },
                buttons: {
                    confirm: {
                        label: 'Да',
                        className: "btn-success btn-lg"
                    },
                    cancel: {
                        label: 'Нет',
                        className: "btn-default btn-lg"
                    }
                }
            });
        }
    },
    Loader: {
        counter: 0,
        getLastId: function () {
            return this.counter;
        },
        init: function (container, message) {
            if (container !== undefined && !(container instanceof jQuery)) {
                container = $(container);
            }
            else if (container === undefined) {
                container = $('body');
            }

            ++this.counter;

            var $loader = $('<div class="_loader_container"><span class="_loader_preloader" /></div>');

            if (message !== undefined) {
                if (message instanceof jQuery)
                    $loader.append(message);
                else
                    $loader.append('<span class="_loader_message">' + message + '</span>');
            }

            return $loader
                .appendTo(container)
                .css({
                    width: container.outerWidth(true),
                    height: container.outerHeight(true),
                    top: container.offset().top - $(window).scrollTop(),
                    left: container.offset().left - $(window).scrollLeft()
                })
                .prop('id', 'loader' + this.getLastId());
        },
        show: function (container, message, speed) {
            var speed = speed || 500;

            this.init(container, message).fadeTo(speed, 0.7);
            return this.counter;
        },
        hide: function (id) {
            if (!id)
                cont = $('._loader_container');
            else
                cont = $('#loader' + id);

            cont.stop().fadeOut(400, function () {
                $(this).remove();
            });
        }
    }
}
App.Form = {
    extend: function (key, data) {
        if (!data) var data = {};

        data['_prefix'] = key;
        App.Form[key] = $.extend(this._decorator, data);
        return App.Form[key];
    },
    _decorator: {
        _id: null,
        _api_url: null,
        _api_method: null,
        _prefix: null,
        _key: null,
        _timestamp: null,
        _isChanged: false,
        _form: null,
        _autoSaveTimer: null,
        _fieldsData: {},
        _submitButton: null,
        messages: {
            saved: null
        },
        fieldsMeta: {},
        _fields: {},
        autoSaveDelay: 5000,
        init: function (form) {
            this._form = form;
            this._key = 'form' + this._prefix + this._id;

            $(this._form).on('click', ':button', $.proxy(function (e) {
                this._submitButton = $(e.target);
            }, this));

            this._fieldsData['timestamp'] = new Date().getTime();

            if ((this._api_url === null || !this._api_url.length) && this._form.attr('action').indexOf('api.') >= 0) {
                this._api_url = this._form.attr('action');
            }

            if (this._api_method === null || !this._api_method.length) {
                var $method = $('input[name="_method"]', this._form);

                this._api_method = $method.size()
                    ? $method.val().toLowerCase()
                    : this._form.prop('method').toLowerCase();
            }

            this._autoSaveTimer = setInterval($.proxy(this.onBackup, this), this.autoSaveDelay);

            this.getFieldsData();
            this._id = this._fieldsData['id'];

            for (i in this.fieldsMeta) {
                switch (this.fieldsMeta[i]) {
                    case 'tags':
                        this._fields[i] = new FieldTags(this._form, i);
                        break;
                    case 'checkbox':
                        this._fields[i] = new FieldCheckbox(this._form, i);
                        break;
                    case 'markdown':
                        this._fields[i] = new FieldMarkdown(this._form, i);
                        break;
                    default:
                        this._fields[i] = new FieldDefault(this._form, i);
                }
            }

            console.log(this);

            this.onLoad();
            $(window).unload($.proxy(this.onUnload, this));
        },
        getFieldsData: function () {
            for (i in this._fields) {
                this._fieldsData[i] = this._getFieldData(i);
            }

            return this._fieldsData;
        },
        setFieldsData: function (data) {
            for (i in this._fields) {
                if (i == 'id') continue;
                this._setFieldData(i, data[i]);
            }
        },
        getField: function (name) {
            return this._fields[name] || null;
        },
        hasField: function(name) {
            return this.fieldsMeta[name] != 'undefined';
        },
        _getFieldData: function (name) {
            if (!this.hasField(name)) return false;
            return this.getField(name).getValue();
        },
        _setFieldData: function (name, value) {
            if (!this.hasField(name)) return false;
            return this.getField(name).setValue(value);
        },

        /******************************************
         * LocalStorage
         ******************************************/
        saveToLocalStorage: function (data) {
            $.jStorage.set(this._key, data);
            this._isChanged = false;
        },
        getFromLocalStorage: function () {
            return $.jStorage.get(this._key);
        },
        clearLocalStorage: function () {
            $.jStorage.deleteKey(this._key);
        },
        /******************************************
         * Validation
         ******************************************/
        clearErrors: function () {
            $('.validation-error').remove();
            $('.form-group').removeClass('has-error');
        },
        onFailValidation: function (errors) {
            for (field in errors) {
                if (!this.hasField(field)) continue;

                var $elm = this.getField(field).getElement();

                $elm.closest('.form-group')
                    .addClass('has-error')
                    .end();

                for (i in errors[field]) {
                    $elm.after($('<p class="help-block validation-error" />').text(errors[field][i]));
                }

            }
        },
        /******************************************
         * Events
         ******************************************/
        onLoad: function () {
            this._form
                .on('submit', $.proxy(this.onSubmit, this))
                .on('change keyup', 'input, select, textarea', $.proxy(this.onChange, this));

            this.showAutoSaveNotify();
        },
        onChange: function (e) {
            e.preventDefault();
            this._isChanged = true;

            // Удаляем уведомление об автосохранении
            $('#notification_autosave').remove();
        },
        onSubmit: function (e) {
            this.clearErrors();

            $(':button', this._form).prop('disabled', true);

            if(this._api_url) {
                e.preventDefault();
                Api[this._api_method](this._api_url, this.getFieldsData(), $.proxy(this.onResponse, this));

                return false;
            }
        },
        onResponse: function (response) {
            $(':button', this._form).prop('disabled', false);
            this.clearLocalStorage();
        },
        /******************************************
         * Backup
         ******************************************/
        showAutoSaveNotify: function () {
            var data = this.getFromLocalStorage();
            if (_.isObject(data) && !_.isEmpty(data)) {
                var time = new Date(data['timestamp']);

                // TODO: добавить локализацию
                this._form.prepend(_.template('<div class="alert alert-info m-b-none" id="notification_autosave">' +
                    'У вас есть автосохранение от <b><%= date %> <%= time %></b>, ' +
                    '<a href="#reset" class="reset_form_from_autosave">восстановить форму</a>?' +
                    '</div>')({
                    date: _getDate(time),
                    time: _getTime(time)
                }));

                // Восстановление
                $('.reset_form_from_autosave').on('click', $.proxy(function (e) {
                    e.preventDefault();
                    this.onRestore(data);
                    $('#notification_autosave').remove();
                }, this));
            }
        },
        onBackup: function (e) {
            if (!this._isChanged) return;

            var data = this.getFieldsData();
            data['timestamp'] = new Date().getTime();

            this.saveToLocalStorage(data);
        },
        onRestore: function (data) {
            this.setFieldsData(data);
        },
        onUnload: function (e) {
            this.onBackup(e);
        }
    }
}

FieldDefault = function(form, name) {
    this._form = form;
    this._name = name;

    this._element = this.getFieldInput();
    this.init();
};

FieldDefault.prototype = {
    init: function() {

    },
    getFieldInput: function() {
        return $(':input[name="' + this.getName() + '"]', this._form);
    },
    getElement: function() {
        return this._element;
    },
    getValue: function() {
        return this.getElement().val();
    },
    setValue: function(value) {
        this.getElement().val(value);
    },
    getName: function() {
        return this._name;
    }
};

FieldCheckbox = function(form, name) {
    FieldDefault.apply(this, arguments);
}

FieldCheckbox.prototype.__proto__ = FieldDefault.prototype;

FieldCheckbox.prototype.getValue = function() {
   return this.getElement().prop('checked')
};

FieldCheckbox.prototype.setValue = function(value) {
    return this.getElement().prop('checked', value)
};


FieldMarkdown = function(form, name) {
    FieldDefault.apply(this, arguments);
}

FieldMarkdown.prototype.__proto__ = FieldDefault.prototype;

FieldMarkdown.prototype.init = function() {
    var $elm = this.getElement();
    this.editor = new SimpleMDE({
        element: $elm[0]
    });
};

FieldMarkdown.prototype.getValue = function() {
    return this.editor.value();
};

FieldMarkdown.prototype.setValue = function(value) {
    return this.editor.value(value);
};

FieldTags = function(form, name) {
    FieldDefault.apply(this, arguments);
}

FieldTags.prototype.__proto__ = FieldDefault.prototype;

FieldTags.prototype.getValue = function() {
    return this.getElement().val().split(',');
};

FieldTags.prototype.setValue = function(value) {
    return this.getElement().val(value.join()).trigger('change');
};


function extend(Child, Parent) {
    var F = function() { }
    F.prototype = Parent.prototype
    Child.prototype = new F()
    Child.prototype.constructor = Child
    Child.superclass = Parent.prototype
}

// TODO: добавить локализацию
function _getDate(d) {
    var month_names = new Array("января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");
    var current_date = d.getDate();
    var current_month = d.getMonth();
    var current_year = d.getFullYear();
    return current_date + " " + month_names[current_month] + " " + current_year
}

function _getTime(currentTime) {
    var hours = currentTime.getHours();
    var minutes = currentTime.getMinutes();
    if (minutes < 10) {
        minutes = "0" + minutes
    }
    return hours + ":" + minutes
}
var Api = {
    _response: null,
    get: function (uri, data, callback, async) {
        return this.request('GET', uri, data, callback, async);
    },
    post: function (uri, data, callback, async) {
        return this.request('POST', uri, data, callback, async);
    },
    put: function (uri, data, callback, async) {
        return this.request('PUT', uri, data, callback, async);
    },
    'delete': function (uri, data, callback, async) {
        return this.request('DELETE', uri, data, callback, async);
    },
    request: function (method, uri, data, callback, async) {
        var url = this.parseUrl(uri);

        $.ajaxSetup({
            contentType: 'application/json'
        });

        if (data instanceof jQuery) {
            data = Api.serializeObject(data);
        }

        if (typeof(data) == 'object' && method.toLowerCase() != 'get') {
            data = JSON.stringify(data);
        }

        return $.ajax({
            type: method,
            url: url,
            data: data,
            dataType: 'json',
            async: async !== false
        })
            .done(function (response) {
                this._response = response;

                if (response.code != 200)
                    return Api.exception(response, callback);

                window.top.$('body').trigger(Api.getEventKey(method, url), [this._response]);

                if (response.message && response.code == 200)
                    App.Messages.show(response.message, 'information');

                if (response.popup && response.code == 200)
                    Popup.openHTML(response.popup);

                if (typeof(callback) == 'function') callback(this._response);
            })
            .fail(function (e) {
                return Api.exception(e.responseJSON, callback);
            });
    },
    parseUrl: function (url) {
        return url;
    },
    getEventKey: function (method, url) {
        var event = method + url.replace(SITE_URL, ":").replace(/\//g, ':');
        return event.toLowerCase()
    },
    serializeObject: function (form) {
        var json = {},
            push_counters = {},
            patterns = {
                "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
                "key": /[a-zA-Z0-9_]+|(?=\[\])/g,
                "push": /^$/,
                "fixed": /^\d+$/,
                "named": /^[a-zA-Z0-9_]+$/
            };

        var build = function (base, key, value) {
            base[key] = value;
            return base;
        };

        var push_counter = function (key) {
            if (push_counters[key] === undefined) {
                push_counters[key] = 0;
            }
            return push_counters[key]++;
        };

        $.each(form.serializeArray(), function () {
            // skip invalid keys
            if (!patterns.validate.test(this.name)) {
                return;
            }
            var k,
                keys = this.name.match(patterns.key),
                merge = this.value,
                reverse_key = this.name;

            while ((k = keys.pop()) !== undefined) {
                // adjust reverse_key
                reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');
                // push
                if (k.match(patterns.push)) {
                    merge = build([], push_counter(reverse_key), merge);
                }
                // fixed
                else if (k.match(patterns.fixed)) {
                    merge = build([], k, merge);
                }
                // named
                else if (k.match(patterns.named)) {
                    merge = build({}, k, merge);
                }
            }
            json = $.extend(true, json, merge);
        });
        return json;
    },
    exception: function (response, callback) {
        if (typeof(callback) == 'function')
            callback(response);

        switch (response.code) {
            case 220: // ERROR_PERMISSIONS

                break;
            case 110: // ERROR_MISSING_PAPAM
                App.Messages.show(response.message, 'error', 'fa fa-exclamation-triangle');
                break;
            case 120: // ERROR_VALIDATION
                for (i in response.errors) {
                    App.Messages.show(response.errors[i], 'error', 'fa fa-exclamation-triangle');
                }
                break;
            case 130: // ERROR_UNKNOWN
            case 140: // ERROR_TOKEN
            case 150: // ERROR_MISSING_ASSIGMENT

                break;
            case 301: // Redirect
            case 302: // Redirect
                window.location.href = response.targetUrl;
                break;
            case 403: // ERROR_UNAUTHORIZED
            case 404: // ERROR_PAGE_NOT_FOUND
                break;
            default:
                App.Messages.show(response.message, 'error', 'fa fa-exclamation-triangle');
        }
    },
    response: function () {
        return this._response;
    }
};
App.Components
    .add('ajaxSetup', function () {
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
    })
    .add('i18nSetup', function () {
        i18n.init({
            lng: 'ru',
            fallbackLng: [],
            resGetPath: '/js/lang/__lng__.json',
        });
    })
    .add('select', function () {
        $("select").select2();
    })
    .add('notySetup', function () {
        $.noty.defaults = $.extend($.noty.defaults, {
            layout: 'topRight',
            theme: 'relax',
            timeout: 3000
        });
    })
    .add('validator.default', function () {
        $.validator.setDefaults({
            highlight: function (element) {
                var elem = $(element);
                if (elem.hasClass("select2-offscreen")) {
                    $("#s2id_" + elem.attr("id") + " ul")
                        .closest('.form-group')
                        .addClass('has-error');
                } else {
                    elem
                        .closest('.form-group')
                        .addClass('has-error');
                }
            },
            unhighlight: function (element) {
                var elem = $(element);

                if (elem.hasClass("select2-offscreen")) {
                    $("#s2id_" + elem.attr("id") + " ul")
                        .closest('.form-group')
                        .removeClass('has-error')
                        .find('help-block-hidden')
                        .removeClass('help-block-hidden')
                        .addClass('help-block')
                        .show();
                } else {
                    elem
                        .closest('.form-group')
                        .removeClass('has-error')
                        .find('help-block-hidden')
                        .removeClass('help-block-hidden')
                        .addClass('help-block')
                        .show();
                }
            },
            errorElement: 'p',
            errorClass: 'jquery-validate-error help-block',
            errorPlacement: function (error, element) {
                var $p, has_e, is_c;
                is_c = element.is('input[type="checkbox"]') || element.is('input[type="radio"]');
                has_e = element.closest('.form-group').find('.jquery-validate-error').length;
                if (!is_c || !has_e) {
                    if (!has_e) {
                        element.closest('.form-group').find('.help-block').removeClass('help-block').addClass('help-block-hidden').hide();
                    }
                    error.addClass('help-block');
                    if (is_c) {
                        return element.closest('[class*="col-"]').append(error);
                    } else {
                        $p = element.parent();
                        if ($p.is('.input-group')) {
                            return $p.parent().append(error);
                        } else {
                            return $p.append(error);
                        }
                    }
                }
            }
        });
    })
    .add('tags', function () {
        $('textarea.input-tags').select2({
            tags: [],
            minimumInputLength: 0,
            tokenSeparators: [',', ' ', ';'],
            createSearchChoice: function (term, data) {
                if ($(data).filter(function () {
                        return this.text.localeCompare(term) === 0;
                    }).length === 0) {
                    return {
                        id: term,
                        text: term
                    };
                }
            },
            multiple: true,
            ajax: {
                url: '/api.tags',
                dataType: "json",
                data: function (term, page) {
                    return {tag: term};
                },
                results: function (data, page) {
                    if (!data.content) return {results: []};
                    return {results: data.content};
                }
            },
            initSelection: function (element, callback) {
                var data = [];

                var tags = element.val().split(",");
                for (i in tags) {
                    data.push({
                        id: tags[i],
                        text: tags[i]
                    });
                };
                callback(data);
            }
        });
    })
    .add('datepicker', function () {
        var options = {
            format: 'Y-m-d H:i:00',
            lang: LOCALE,
            dayOfWeekStart: 1
        };

        $('.input-datetime').each(function () {
            var local_options = $.extend({}, options);
            var $self = $(this);

            if ($self.data('range-max-input')) {
                local_options['onShow'] = function (ct) {
                    var $input = $($self.data('range-max-input'));
                    this.setOptions({
                        maxDate: $input.val() ? $input.val() : false
                    });
                }
            }

            if ($self.data('range-min-input')) {
                local_options['onShow'] = function (ct) {
                    var $input = $($self.data('range-min-input'));
                    this.setOptions({
                        minDate: $input.val() ? $input.val() : false
                    });
                }
            }

            $self.datetimepicker(local_options);
        });

        $('.input-date').each(function () {
            var local_options = $.extend(options, {
                timepicker: false,
                format: 'Y-m-d'
            });

            var $self = $(this);

            if ($self.data('range-max-input')) {
                local_options['onShow'] = function (ct) {
                    var $input = $($self.data('range-max-input'));
                    this.setOptions({
                        maxDate: $input.val() ? $input.val() : false
                    });
                }
            } else if ($self.data('range-min-input')) {
                local_options['onShow'] = function (ct) {
                    var $input = $($self.data('range-min-input'));
                    this.setOptions({
                        minDate: $input.val() ? $input.val() : false
                    });
                }
            }

            $self.datetimepicker(local_options);
        });
    })
    .add('icon', function () {
        $('*[data-icon]').add('*[data-icon-prepend]').each(function () {
            var cls = $(this).data('icon');
            if ($(this).hasClass('btn-labeled')) cls += ' btn-label icon';

            $(this).html('<i class="fa fa-' + cls + '"></i> ' + $(this).html());
            $(this).removeAttr('data-icon-prepend').removeAttr('data-icon');
        });

        $('*[data-icon-append]').each(function () {
            $(this).html($(this).html() + '&nbsp&nbsp<i class="fa fa-' + $(this).data('icon-append') + '"></i>');
            $(this).removeAttr('data-icon-append');
        });
    });
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
$(function () {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	runApplication();

	function runApplication() {
		App.Components.init();
		App.Controllers.call();
		App.Messages.init();
	}
});
//# sourceMappingURL=app.js.map
