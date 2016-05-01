<?php defined('SYSPATH') or die('No direct script access.');
// old vs new arrays http://php.net/manual/en/language.types.array.php

// views
return [
	'views' => [
		'path' => 'templates',						// in application / templates /
		'layout' => '_layout.php',					// main layout file
		'error'  => '_error.php',					// main layout file
		'partials' => [								// partial views
			'content'  => '_content.php',
			'mikka'    => '_mikka.php',
			]
		]
	];

// eof
