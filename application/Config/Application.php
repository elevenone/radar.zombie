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
// use Aura\Payload\Payload;
// use Aura\Payload_Interface\PayloadStatus;
use Relay\Middleware\ExceptionHandler;
use Relay\Middleware\ResponseSender;
use Zend\Diactoros\Response as Response;
use Portfolio\Domain\Entity\Post as PostEntity;

// use Whoops\Run;

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
         * Parameters
         */

        /**
         * Aura.view
         */
        // view files and paths from class
        $viewsconfig = new \Application\Config\AuraViews;
        $views = $viewsconfig->getViews();

        $di->params['Application\Responder\AuraViewResponder']['views'] = $views;
        $di->params['Application\Responder\AuraViewPayloadResponder']['views'] = $views;
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
         * Middleware
         */
        $adr->middle(new ResponseSender());
        $adr->middle(new ExceptionHandler(new Response()));
        $adr->middle('Radar\Adr\Handler\RoutingHandler');
        $adr->middle('Radar\Adr\Handler\ActionHandler');
		//

		// $adr->middle(new Whoops\Run());
		// $container->register(new \WhoopsPimple\WhoopsServiceProvider);


        /**
         * Input
         */
        // $adr->input('Application\Input\MergedArray');
        // $adr->input('Application\Input\NoneExpected');

        /**
         * Responder
         */
        $adr->responder('Application\Responder\AuraViewResponder');

        /**
         * Routes
         */

        // demo route
        $adr->get('Hello', '/hello/{name}?', function (array $input) {
                $payload = new Payload();
                return $payload
                    ->setStatus(PayloadStatus::SUCCESS)
                    ->setOutput([
                        'phrase' => 'Hello ' . $input['name']
                    ]);
            })
            ->defaults(['name' => 'world']);



        // app routes

        // static page views route
        $adr->get('staticpage', '/page/{page}?', \Application\Domain\Hello::class)
            // ->input('Application\Input\MergedArray')
            ->responder('Application\Responder\AuraViewPayloadResponder')
            ->defaults(['page' => 'mikka']);



        // $adr->get('site.index', '/class/{name}?', ['Application\Domain\Index', '_invoke'])
        //     ->defaults(['name' => 'mikkamakka']);

		// the responder here uses an array from the action
        $adr->get('index', '/{name}?', \Application\Domain\Hello::class)
            // ->input('Application\Input\NoneExpected')
            // ->responder('Portfolio\Delivery\Responder\AuraViewResponder')
            ->defaults(['name' => 'world']);

		// the responder here uses aura.payload object from the action
        $adr->get('index2', '/payload/{page}?', \Application\Domain\HelloPayload::class)
            // ->input('Application\Input\NoneExpected')
            ->responder('Application\Responder\AuraViewPayloadResponder')
            ->defaults(['page' => 'mikka']);

    }



}
