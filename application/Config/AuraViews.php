<?php

namespace Application\Config;

class AuraViews
{
    //
    public $views;

    function __construct()
    {

        //
        $template_path = realpath( __DIR__ . '/../templates');
        $staticpages_path = $template_path . '/staticpages';
        $partials_path = $template_path . '/partials';

        $views = [
            'views' => [

                // paths
                'path' => $template_path,
                'staticpages_path' => $staticpages_path,
                'partials_path' => $partials_path,

                'layout' => '/layout.php',

                'partials' => [
                    'content' => '/_content.php',
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
