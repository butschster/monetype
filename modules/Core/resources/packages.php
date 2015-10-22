<?php

Package::add('libraries')
        ->js(null, url('js/lib.js'));

Package::add('app')
        ->css(null, url('css/app.css'))
        ->js(null, url('js/app.js'), 'libraries');

Package::add('coming_soon')
    ->css(null, url('css/coming_soon.css'))
    ->js(null, url('js/coming_soon.js'), 'libraries')
    ->js('recaptcha', 'https://www.google.com/recaptcha/api.js');

Package::add('dropzone')
        ->css('dropzone.basic', url('libs/dropzone/basic.min.css'))
        ->css(null, url('libs/dropzone/dropzone.min.css'))
        ->js(null, url('libs/dropzone/dropzone.min.js'), 'libraries');

Package::add('simplemde')
    ->css(null, url('libs/simplemde/simplemde.css'))
    ->js(null, url('libs/simplemde/simplemde.js'), 'libraries');

Package::add('backstretch')
        ->js(null, url('libs/jquery.backstretch/jquery.backstretch.min.js'), 'libraries');

Package::add('countdown')
    ->js(null, url('libs/jquery.countdown.js'), 'libraries');

Package::add('select2')
       ->css(null, url('libs/select2/css/select2.min.css'))
       ->js(null, url('libs/select2/js/select2.min.js.js'), 'libraries');

Package::add('datepicker')
       ->css(null, url('libs/datetimepicker/jquery.datetimepicker.css'))
       ->js(null, url('libs/datetimepicker/js/jquery.datetimepicker.js'), 'libraries');

Package::add('validation')
       ->js(null, url('libs/validation/jquery.validate.min.js'), 'libraries');