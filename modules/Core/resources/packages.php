<?php

Package::add('libraries')
	->js(null, url('js/lib.js'));

Package::add('app')
	->css(null, url('css/app.css'))
	->js(null, url('js/app.js'), 'libraries');