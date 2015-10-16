<?php

Package::add('libraries')
        ->js(null, url('js/lib.js'));

Package::add('app')
        ->css(null, url('css/app.css'))
        ->js(null, url('js/app.js'), 'libraries');

Package::add('dropzone')
        ->css('dropzone.basic', url('libs/dropzone/basic.min.css'))
        ->css(null, url('libs/dropzone/dropzone.min.css'))
        ->js(null, url('libs/dropzone/dropzone.min.js'), 'libraries');

Package::add('backstretch')
        ->js(null, url('libs/jquery.backstretch/jquery.backstretch.min.js'), 'libraries');