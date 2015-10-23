var gulp = require('gulp'),
    elixir = require('laravel-elixir');

var paths = {
    'assets': elixir.config.assetsPath,
    'assetsCss': elixir.config.assetsPath + '/css/',
    'assetsSass': elixir.config.assetsPath + '/sass/',
    'assetsJs': elixir.config.assetsPath + '/js/',
    'bootstrap': './bower_components/bootstrap/',
    'jquery': './bower_components/jquery/',
    'underscore': './bower_components/underscore/',
    'datetimepicker': './bower_components/datetimepicker/',
    'fontawesome': './bower_components/fontawesome/',
    'jqueryvalidation': './bower_components/jquery-validation/',
    'jquerytagsinput': './bower_components/jquery.tagsinput/',
    'noty': './bower_components/noty/',
    'select2': './bower_components/select2/',
    'i18next': './bower_components/i18next/',
    'jStorage': './bower_components/jStorage/',
    'charts': './bower_components/Chart.js/',
    'dropzone': './bower_components/dropzone/',
    'icons': './resources/assets/icons/',
};

elixir.config.sourcemaps = false;

elixir(function (mix) {
    mix
    // SaSS
    .sass('bootstrap.scss', paths.assetsCss)
    .sass('app.scss', paths.assetsCss)

    // Less
    //    .less('app.less', 'public/css/')
    //    .less('coming_soon.less', 'public/css/')
    //    .less('simplemde.less', 'public/libs/simplemde')

        // CSS
        .styles([
            paths.datetimepicker + 'jquery.datetimepicker.css'
        ], 'public/libs/datetimepicker/jquery.datetimepicker.css')

        // Fonts
        .copy(paths.icons + 'font/**', 'public/fonts')

        // JavaScript
        .scripts([
            paths.jquery + 'dist/jquery.js',
            paths.bootstrap + "js/dropdown.js",
            paths.bootstrap + "js/collapse.js",
            paths.underscore + "underscore.js",
            paths.noty + "js/noty/packaged/jquery.noty.packaged.js",
            paths.jStorage + "jstorage.js"
        ], 'public/js/lib.js')
        .scripts([
            'core/app.js',
            'core/form.js',
            'core/api.js',
            'components.js',
            'controllers/articles.js',
            'run.js'
        ], 'public/js/app.js')
        .scripts([
            'core/app.js',
            'components.js',
            'controllers/comingSoon.js',
            'run.js'
        ], 'public/js/coming_soon.js')
        .scripts([
            'libs/simplemde.js'
        ], 'public/libs/simplemde/simplemde.js')
        .scripts([
            paths.datetimepicker + 'jquery.datetimepicker.js'
        ], 'public/libs/datetimepicker/jquery.datetimepicker.js')
        .copy(paths.charts + 'Chart.min.js', 'public/libs/chart')
        .copy(paths.dropzone + 'dist/min/**', 'public/libs/dropzone')
        .copy(paths.select2 + 'dist/**', 'public/libs/select2')
        .copy(paths.jqueryvalidation + 'dist/**', 'public/libs/validation');
});