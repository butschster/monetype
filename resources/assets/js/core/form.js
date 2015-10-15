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
        fields: {},
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

            this.onLoad();
            $(window).unload($.proxy(this.onUnload, this));
        },
        getFieldsData: function () {
            for (i in this.fields)
                this._fieldsData[i] = this._getFieldData(i);

            return this._fieldsData;
        },
        setFieldsData: function (data) {
            for (i in this.fields) {
                if (i == 'id') continue;
                this._setFieldData(i, data[i]);
            }
        },
        getField: function (name) {
            switch (this.fields[name]) {
                case 'multiple':
                    return $(':input[name="' + name + '[]"]', this._form)
                default:
                    return $(':input[name="' + name + '"]', this._form)
            }
        },
        _getFieldData: function (name) {
            if (!this.fields[name]) return null;

            var $elm = this.getField(name);

            switch (this.fields[name]) {
                case 'checkbox':
                    return $elm.prop('checked');
                case 'tags':
                    return $elm.val().split(',');
                default:
                    return $elm.val();
            }
        },
        _setFieldData: function (name, value) {
            if (!this.fields[name]) return false;

            var $elm = this.getField(name);

            switch (this.fields[name]) {
                case 'checkbox':
                    $elm.prop('checked', value);
                    break;
                case 'tags':
                    $elm.val(value.join()).trigger('change');
                    break;
                case 'multiple':
                    $elm.val(value).trigger('change');
                    break;
                default:
                    $elm.val(value).trigger('change');
            }
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
                if (!this.fields[field]) continue;

                var $elm = this.getField(field)

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

            switch (response.code) {
                case 120: // Validation
                    return this.onFailValidation(response.errors);
                    break;
                case 200:
                    if (this.messages.saved.length > 0)
                        noty({text: this.messages.saved, type: 'success'});
                    break;
                default:

                    break;
            }
        },
        /******************************************
         * Backup
         ******************************************/
        showAutoSaveNotify: function () {
            var data = this.getFromLocalStorage();
            if (_.isObject(data) && !_.isEmpty(data)) {
                var time = new Date(data['timestamp']);

                // TODO: добавить локализацию
                this._form.prepend(_.template('<div class="alert alert-warning m-b-none" id="notification_autosave">' +
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

            // Store data to the storage
            this.saveToLocalStorage(data);
        },
        onRestore: function (data) {
            // Restore data from the storage
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