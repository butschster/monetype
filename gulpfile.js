var gulp = require('gulp'),
    elixir = require('laravel-elixir');

var paths = {
    'assets': elixir.config.assetsPath,
    'assetsCss': elixir.config.assetsPath + '/css/',
    'assetsSass': elixir.config.assetsPath + '/sass/',
    'assetsJs': elixir.config.assetsPath + '/js/',
    'bootstrap': './node_modules/bootstrap-sass/assets/',
    'jquery': './bower_components/jquery/',
    'underscore': './bower_components/underscore/',
    'datetimepicker': './bower_components/datetimepicker/',
    'fontawesome': './bower_components/fontawesome/',
    'jqueryvalidation': './bower_components/jquery-validation/',
    'jquerytagsinput': './bower_components/jquery.tagsinput/',
    'noty': './bower_components/noty/',
    'select2': './bower_components/select2/',
    'i18next': './bower_components/i18next/'
};


elixir(function (mix) {
    mix
        // SaSS
        //.sass('bootstrap.scss', paths.assetsCss)
        //.sass('app.scss', paths.assetsCss)

        // Less
        .less('app.less', paths.assetsCss)

        // CSS
        .styles([
            //paths.assetsCss + 'bootstrap.css',
            paths.select2 + "dist/css/select2.css",
            paths.datetimepicker + "jquery.datetimepicker.css",
            paths.jquerytagsinput + "src/jquery.tagsinput.css",
            paths.assetsCss + 'app.css'
        ], 'public/css/app.css')

        // Fonts
        .copy(paths.bootstrap + 'fonts/bootstrap/**', 'public/fonts')
        .copy(paths.fontawesome + 'fonts/**', 'public/fonts')

        // JavaScript
        .scripts([
            paths.jquery + 'dist/jquery.js',
            paths.bootstrap + "javascripts/bootstrap.js",
            paths.underscore + "underscore.js",
            paths.select2 + "dist/js/select2.full.js",
            paths.noty + "js/noty/packaged/jquery.noty.packaged.js",
            paths.jqueryvalidation + "dist/jquery.validate.js",
            paths.jqueryvalidation + "dist/additional-methods.js",
            paths.i18next + "i18next.js"
        ], 'public/js/lib.js')
        .scripts([
            'core/app.js',
            'core/form.js',
            'core/api.js',
            'components.js',
            'controllers/articles.js',
            'run.js'
        ], 'public/js/app.js')

        // Versioning
        //.version(['public/css/app.css', 'public/js/app.js']);
});