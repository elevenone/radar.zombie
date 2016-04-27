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
        // Aura.view
        // views
        $path_to_config_file;
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
        // aura
        // $di->params['Application\Responder\AuraViewResponder']['viewDir'] = $views['views']['path'];
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

        /**
         * Input
         */
        $adr->input('Application\Input\MergedArray');
        // $adr->input('Application\Input\NoneExpected');

        /**
         * Responder
         */
        // $adr->responder('Application\Responder\HtmlResponder');
        $adr->responder('Application\Responder\AuraViewResponder');

        /**
         * Routes
         */
        $adr->get('Hello', '/hello/{name}?', function (array $input) {
                $payload = new Payload();
                return $payload
                    ->setStatus(PayloadStatus::SUCCESS)
                    ->setOutput([
                        'phrase' => 'Hello ' . $input['name']
                    ]);
            })
            ->defaults(['name' => 'world']);

        // $adr->get('site.index', '/class/{name}?', ['Application\Domain\Index', '_invoke'])
        //     ->defaults(['name' => 'mikkamakka']);

		// the responder here uses an array from the action
        $adr->get('index', '/{name}?', \Application\Domain\Hello::class)
            // ->input('Application\Input\NoneExpected')
            // ->responder('Portfolio\Delivery\Responder\AuraViewResponder')
            ->defaults(['name' => 'world']);

		// the responder here uses aura.payload object from the action
        $adr->get('index2', '/payload/{name}?', \Application\Domain\HelloPayload::class)
            // ->input('Application\Input\NoneExpected')
            ->responder('Application\Responder\AuraViewPayloadResponder')
            ->defaults(['name' => 'world']);

    }



}
