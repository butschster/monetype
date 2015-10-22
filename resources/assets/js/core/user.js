App.User = {
    checkAuth: function() {
        return USER_ID;
    },
    runIfGuest: function(callback, message) {
        if (!this.checkAuth() && typeof callback === 'function') {
            callback()
        }

        if(message) App.Messages.error(message)
    },
    runIfAuth: function(callback, message) {
        if (this.checkAuth() && typeof callback === 'function') {
            return callback()
        }

        if(message) App.Messages.error(message)
    }
}