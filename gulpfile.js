var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix
        .less('app.less')
        .scripts([
            'jquery/js/jquery.min.js',
            'bootstrap/js/bootstrap.js',
            'noty/js/jquery.noty.packaged.js',
            'select2/js/select2.full.js',
            'jquery.tagsinput/js/jquery.tagsinput.js',
            'jquery-validation/js/jquery.validate.js',
            'jquery-validation/js/additional-methods.js',
            'underscore/js/underscore-min.js',
            'jStorage/js/jstorage.js',
            'datetimepicker/js/jquery.datetimepicker.js'
        ], 'public/js/libraries.js', 'public/libs/')
        .scripts([
            'core/app.js',
            'core/form.js',
            'core/api.js',
            'components.js',
            'controllers/articles.js',
            'run.js'
        ], 'public/js/app.js');
});
