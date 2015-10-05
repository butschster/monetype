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