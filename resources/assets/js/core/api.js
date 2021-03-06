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