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
use Aura\Payload\Payload; // demo route
use Aura\Payload_Interface\PayloadStatus; // demo route
use Relay\Middleware\ExceptionHandler;
use Relay\Middleware\ResponseSender;
use Zend\Diactoros\Response as Response;



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
     * Define Aura\View and Aura\Html factories and services
     *
     * @param Container $di DI Container
     *
     * @return void
     *
     * @access public
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function definexxx(Container $di)
    {

    }

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
        // Aura\Html
        $di->set(
            'aura/html:factory',
            $di->lazyNew(HelperLocatorFactory::class)
        );
        $di->set(
            'aura/html:helpers',
            $di->lazyGetCall(
                'aura/html:factory',
                'newInstance'
            )
        );

        // Aura\View
        $di->set(
            'aura/view:factory',
            $di->lazyNew(ViewFactory::class)
        );
        $di->set(
            'aura/view:view',
            $di->lazyGetCall(
                'aura/view:factory',
                'newInstance',
                $di->lazyGet('aura/html:helpers')
            )
        );


        // view files and paths from class
        $viewsconfig = new \Application\Config\AuraViews;
        $views = $viewsconfig->getViews();

        // binding variables to classes
        $di->params['Application\Responder\AuraViewStaticPage']['views'] = $views;
        $di->params['Application\Responder\AuraViewResponder']['views'] = $views;
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



        /**
         *
         * Index page route
         *
         */
        $adr->get('index.page', '/{page}?', \Application\Domain\HelloPayload::class)
            ->input('Application\Input\MergedArray')
            ->responder('Application\Responder\AuraViewStaticPage')
            ->defaults([
                'page' => 'index'
            ]);

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
        $adr->get('static.page', '/page{/page}?', \Application\Domain\HelloPayload::class)
            ->input('Application\Input\MergedArray')
            ->responder('Application\Responder\AuraViewStaticPage')
            ->defaults([
                'page' => 'index'
            ])
            ->tokens([
                'page' => '|index|mikka|mikka2|mikka3'
            ]);

    }

}
