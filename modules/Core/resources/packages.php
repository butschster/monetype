<?php

Package::add('libraries')
	->js(null, url('js/libraries.js'));

Package::add('app')
	->css(null, url('css/app.css'))
	->js(null, url('js/app.js'), 'libraries');