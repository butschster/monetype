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
App.Components.add('register.validator', function() {
	$.countdown.setDefaults($.countdown.regionalOptions['ru']);
	$('#defaultCountdown').countdown({
		until: new Date(2016, 1-1, 1)
	});

	$('.cooming-soon-content').backstretch([
		"/img/coming_soon.jpg"
	], {fade: 1000});

	// Validation for login form
	$("#registerForm").validate({
		rules: {
			email: {
				required: true,
				email: true
			},
			password: {
				required: true,
				minlength: 3,
				maxlength: 20
			},
			password_confirmation: {
				required: true,
				minlength: 3,
				maxlength: 20,
				equalTo: 'input[name="password"]'
			}
		},

		// Messages for form validation
		messages: {
			email: {
				required: 'Please enter your email address',
				email: 'Please enter a VALID email address'
			},
			password: {
				required: 'Please enter your password'
			},
			password_confirmation: {
				required: 'Please enter your password one more time',
				equalTo: 'Please enter the same password as above'
			}
		}
	});
});
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
//# sourceMappingURL=coming_soon.js.map
