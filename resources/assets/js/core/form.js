App.Form = {
    extend: function (key, data) {
        if (!data) var data = {};

        data['_prefix'] = key;
        App.Form[key] = $.extend({}, this._decorator, data);
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

            this._id = this._fieldsData['id'];

            for (i in this.fieldsMeta) {
                if(this.fieldsMeta[i] in  App.Form.Field) {
                    var field = Object.create(App.Form.Field[this.fieldsMeta[i]]);
                } else {
                    var field = Object.create(App.Form.Field['default']);
                }

                field.construct(this, i);

                if(field.getElement().length) {
                    field._init();
                    this._fields[i] = field;
                }
            }

            this.getFieldsData();

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
            return (name in this.fieldsMeta);
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
                this._form.prepend(_.template('<div class="alert alert-info m-b-none autoSaveNotification">' +
                    'У вас есть автосохранение от <b><%= date %> <%= time %></b>, ' +
                    '<a href="#restore" class="autoSaveNotification--restore">восстановить форму</a>?' +
                    '<span class="close autoSaveNotification--close" onclick="">x</span>' +
                    '</div>')({
                    date: _getDate(time),
                    time: _getTime(time)
                }));

                // Восстановление
                $('.autoSaveNotification').on('click', '.autoSaveNotification--restore', $.proxy(function (e) {
                    e.preventDefault();
                    this.onRestore(data);
                    $(this).closest('.autoSaveNotification').remove();
                }, this));

                $('.autoSaveNotification').on('click', '.autoSaveNotification--close', $.proxy(function (e) {
                    e.preventDefault();
                    this.clearLocalStorage();
                    $(this).closest('.autoSaveNotification').remove();
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