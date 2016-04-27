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
		            'content' => '/partials/_content.php',
		            // 'header' => '/partials/_header.php',
		            // 'footer' => '/partials/_footer.php',
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
