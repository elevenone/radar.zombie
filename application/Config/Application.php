<?php
/**
 * Application config
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
    private $views;
    
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

        $di->params['Radar\Adr\Handler\RoutingHandler']['matcher'] = $di->lazyGetCall('radar/adr:router', 'getMatcher');

        // static page responder
        $di->params['Application\Responder\AuraViewStaticPage']['views'] = $views;



        //
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



        //$container->register(new \WhoopsPimple\WhoopsServiceProvider);


        /**
         * Input
         */
        // $adr->input('Application\Input\MergedArray');
        // $adr->input('Application\Input\NoneExpected');

        /**
         * Responder
         */
        // $adr->responder('Application\Responder\AuraViewResponder');

        /**
         * Routes
         */

        // demo route
        /*
        $adr->get('Hello', '/hello/{name}?', function (array $input) {
                $payload = new Payload();
                return $payload
                    ->setStatus(PayloadStatus::SUCCESS)
                    ->setOutput([
                        'phrase' => 'Hello ' . $input['name']
                    ]);
            })
            ->defaults(['name' => 'world']);
        */


        // app routes

        /**
         *
         * Static page views route
         *
         * @param input:
         * @param responder:
         * @param defaults: the default view to view when not defined
         * @return tokens: the allowed values
         *
         */
        $adr->get('staticpage', '/page/{page}?', \Application\Domain\HelloPayload::class)
            ->input('Application\Input\MergedArray')
            ->responder('Application\Responder\AuraViewStaticPage')
            ->defaults(['page' => 'mikka'])
            ->tokens([
                    'page' => '|mikka|mikka2|mikka3'
                ]);





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
            ->defaults(['page' => 'mikka'])
            ->tokens([
                    'page' => '|mikka|mikka2|mikka3'
                ]);

    }


    public function modifyRouter(Container $di)
        {
            $router = $di->get('radar/web-kernel:router');
            
            $router->add('aura.blog.browse', '/blog{/page}')
                        ->setValues(array(
                            'action' => 'aura.blog.browse',
                            'page' => 1
                        ))
                        ->addTokens(array(
                            'page'  => '\d+',
                        ));


            
        }


}
