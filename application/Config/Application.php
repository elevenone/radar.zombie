<?php
/**
 * Project config
 *
 * @category  Config
 * @package   aaa
 *
 * http://blog.andrewshell.org/getting-started-radar/
 * http://auraphp.com/framework/2.x/en/adr
 */
namespace Application\Config;

use PDO;
use Aura\Di\Container;
use Aura\Di\ContainerConfig;
use Aura\Payload\Payload;
use Aura\Payload_Interface\PayloadStatus;
use Relay\Middleware\ExceptionHandler;
use Relay\Middleware\ResponseSender;
use Zend\Diactoros\Response as Response;
use Portfolio\Domain\Entity\Post as PostEntity;

/**
 *
 * DI container configuration for Radar classes.
 *
 * @package radar.zombie
 *
 */
class Application extends ContainerConfig
{
    /**
     *
     * Defines params, setters, values, etc. in the Container.
     *
     * @param Container $di The DI container.
     *
     */
    public function define(Container $di)
    {
        /**
         * Services
         */
        //
		
        /**
         * Environment
         */
        //

        /**
         * Responder
         */
        //

    }

    /**
     *
     * Modifies constructed container objects.
     *
     * @param Container $di The DI container.
     *
     */
    public function modify(Container $di)
    {
        $adr = $di->get('radar/adr:adr');

        /**
         * Routes
         */
		$adr->get('Hello', '/{name}?', function (array $input) {
		        $payload = new Payload();
		        return $payload
		            ->setStatus(PayloadStatus::SUCCESS)
		            ->setOutput([
		                'phrase' => 'Hello ' . $input['name']
		            ]);
		    })
		    ->defaults(['name' => 'world']);

		$adr->get('site.index', '/class/{name}?', ['Application\Domain\Index', '_invoke'])
			->defaults(['name' => 'mikkamakka']);
		
		$adr->get('also', '/also/{name}?', \Application\Domain\Hello::class)
		    ->defaults(['name' => 'world']);


    }



}
