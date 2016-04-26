<?php
// @codingStandardsIgnoreFile

namespace Application\Config;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;

use Service\StatusService;

class Routes extends ContainerConfig
{
    /**
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function modify(Container $di)
    {
        $adr = $di->get('radar/adr:adr');
        // $adr->get('Status','/', StatusService::class);

        $adr->get('index.route', '/{name}', ['Application\\Domain\\Index', '_invoke'])
			// ->responder('Application\Responder\Html')
            ->defaults(['name' => 'zorro']);

        $adr->get('index.route2', '/zzz/{name}', ['Application\\Domain\\Index', '_invoke'] )
			->responder('Application\Responder\AuraView')
            ->defaults(['name' => 'zorro']);


		/**
		 * Routes
		 *
		 * The Domain specification is a string or an array:
		 * * If a string, Radar will instantiate this class using the internal dependency injection container and call its __invoke() method with the user input from the HTTP request.
		 * * If an array in the format ['ClassName', 'method'], the dependency injection container will instantiate the specfied class name, and then call the specified method with the user input from the HTTP request.
		 *
		 */
		// $adr->get('site.index', '/{name}', ['Application\\Domain\\Index', 'index']);
		


    }
}
