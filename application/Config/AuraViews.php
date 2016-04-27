<?php

namespace Application\Config;

class AuraViews
{
	//
	public $views;
		
	function __construct()
	{
		$views = [
		    'views' => [
		        'path' => realpath( __DIR__ . '/../auraview'),
		        'layout' => '/layout.php',
		        'error' => '/_error.php',
		        'partials' => [
		            'content' => '/_content.php',
		            'header' => '/_header.php',
		            'footer' => '/_footer.php',
		            ]
		        ]
		    ];

		$this->views = $views;
	}

	//
    // public function __invoke(){}

	//
	public function getViews()
	{
		return $this->views;
		
	}

}

// eof
