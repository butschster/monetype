<?php

PackageManager::add('libraries')
        ->js(null, url('js/lib.js'));

PackageManager::add('app')
        ->css(null, url('css/app.css'))
        ->js(null, url('js/app.js'), 'libraries');

PackageManager::add('coming_soon')
    ->css(null, url('css/coming_soon.css'))
    ->js(null, url('js/coming_soon.js'), 'libraries')
    ->js('recaptcha', 'https://www.google.com/recaptcha/api.js');

PackageManager::add('dropzone')
        ->css('dropzone.basic', url('libs/dropzone/basic.min.css'))
        ->css(null, url('libs/dropzone/dropzone.min.css'))
        ->js(null, url('libs/dropzone/dropzone.min.js'), 'libraries');

PackageManager::add('simplemde')
    ->css(null, url('libs/simplemde/simplemde.css'))
    ->js(null, url('libs/simplemde/simplemde.js'), 'libraries');

PackageManager::add('backstretch')
        ->js(null, url('libs/jquery.backstretch/jquery.backstretch.min.js'), 'libraries');

PackageManager::add('countdown')
    ->js(null, url('libs/jquery.countdown.js'), 'libraries');

PackageManager::add('select2')
       ->css(null, url('libs/select2/css/select2.min.css'))
       ->js(null, url('libs/select2/js/select2.min.js.js'), 'libraries');

PackageManager::add('datepicker')
       ->css(null, url('libs/datetimepicker/jquery.datetimepicker.css'))
       ->js(null, url('libs/datetimepicker/js/jquery.datetimepicker.js'), 'libraries');

PackageManager::add('rangeslider')
       ->css(null, url('libs/nouislider/nouislider.min.css'))
       ->js(null, url('libs/nouislider/nouislider.min.js'), 'libraries');

PackageManager::add('tagsinput')
       ->js('typeahead', url('libs/bootstrap3-typeahead/bootstrap3-typeahead.min.js'), 'libraries')
       ->js(null, url('libs/bootstrap-tagsinput/bootstrap-tagsinput.min.js'), 'libraries');

PackageManager::add('validation')
       ->js(null, url('libs/validation/jquery.validate.min.js'), 'libraries');