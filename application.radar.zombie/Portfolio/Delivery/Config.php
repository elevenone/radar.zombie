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
namespace Portfolio\Delivery;

use PDO;
use Aura\Di\Container;
use Aura\Di\ContainerConfig;
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
class Config extends ContainerConfig
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
        $di->set('radar/adr:adr', $di->lazyNew('Radar\Adr\Adr'));
        $di->set('radar/adr:resolver', $di->lazyNew('Radar\Adr\Resolver'));
        $di->set('radar/adr:router', $di->lazyNew('Aura\Router\RouterContainer'));
        //
        $di->set('portfolio/domain:postGateway', $di->lazyNew('Portfolio\Data\Gateway\PostSqlite'));
        $di->set('dbh', $di->lazyNew('PDO'));

        /**
         * Aura\Router\Container
         */
        $di->setters['Aura\Router\RouterContainer']['setRouteFactory'] = $di->newFactory('Radar\Adr\Route');

        /**
         * Relay\RelayBuilder
         */
        $di->params['Relay\RelayBuilder']['resolver'] = $di->lazyGet('radar/adr:resolver');

        /**
         * Radar\Adr\Adr
         */
        $di->params['Radar\Adr\Adr']['map'] = $di->lazyGetCall('radar/adr:router', 'getMap');
        $di->params['Radar\Adr\Adr']['rules'] = $di->lazyGetCall('radar/adr:router', 'getRuleIterator');
        $di->params['Radar\Adr\Adr']['relayBuilder'] = $di->lazyNew('Relay\RelayBuilder');

        /**
         * Radar\Adr\Handler\ActionHandler
         */
        $di->params['Radar\Adr\Handler\ActionHandler']['resolver'] = $di->lazyGet('radar/adr:resolver');

        /**
         * Radar\Adr\Handler\RoutingHandler
         */
        $di->params['Radar\Adr\Handler\RoutingHandler']['matcher'] = $di->lazyGetCall('radar/adr:router', 'getMatcher');
        $di->params['Radar\Adr\Handler\RoutingHandler']['actionFactory'] = $di->lazyNew('Arbiter\ActionFactory');

        /**
         * Radar\Adr\Resolver
         */
        $di->params['Radar\Adr\Resolver']['injectionFactory'] = $di->getInjectionFactory();








        // if (!file_exists(__DIR__ . '/../../database')) {
            // mkdir(__DIR__ . '/../../database');
        // }

        $dataDir = realpath(__DIR__ . '/../../database');
        $di->params['PDO']['dsn'] = 'sqlite:' . $dataDir . '/db.sqlite';
        $di->params['PDO']['username'] = '';
        $di->params['PDO']['passwd'] = '';
        $di->params['PDO']['options'] = [];
        $di->params['Portfolio\Data\Gateway\PostSqlite']['dbh'] = $di->lazyGet('dbh');

        // Aura.view
        // views
        $path_to_config_file; 
        $views = [
            'views' => [
                'path' => realpath( __DIR__ . '/../../auraview'),
                'layout' => '_layout.php',
                'error' => '_error.php',
                'partials' => [
                    'content' => '_content.php',
                    'header' => '_header.php',
                    'footer' => '_footer.php',
                    ]
                ]
            ];

        // Aura.view responder $iewDir param
        $di->params['Portfolio\Delivery\Responder\AuraViewResponder']['viewDir'] = '$views['views']['path']';
        
        // @array view files and paths 
//        $di->params['Portfolio\Delivery\Responder\AuraViewResponder']['views'] = $views;

        // Html responder $iewDir param
        $di->params['Portfolio\Delivery\Responder\HtmlResponder']['viewDir'] = __DIR__ . '/../../views';

        // register domains
        $di->params['Portfolio\Domain\Interactor\ListAllPosts']['postGateway'] = $di->lazyGet('portfolio/domain:postGateway');
        $di->params['Portfolio\Domain\Interactor\DisplaySinglePost']['postGateway'] = $di->lazyGet('portfolio/domain:postGateway');
        $di->params['Portfolio\Domain\Interactor\CreateNewPost']['postGateway'] = $di->lazyGet('portfolio/domain:postGateway');
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
        $this->adr = $adr;

        // Middleware
        $adr->middle(new ResponseSender());
        $adr->middle(new ExceptionHandler(new Response()));
        $adr->middle('Radar\Adr\Handler\RoutingHandler');
        $adr->middle('Radar\Adr\Handler\ActionHandler');

        // Custom input and responder
        $adr->input('Portfolio\Delivery\Input\MergedArray');
        $adr->responder('Portfolio\Delivery\Responder\HtmlResponder');
        // $adr->responder('Portfolio\Delivery\Responder\AuraViewResponder');

        // Populate database with some demo stuff
        // $postGateway = $di->get('blog/domain:postGateway');
        // $postGateway->savePost(new PostEntity('Sample Post 1', 'This is the first sample post.', '', '1'));
        // $postGateway->savePost(new PostEntity('Sample Post 2', 'This is the second sample post.', '', '2'));
        // $postGateway->savePost(new PostEntity('Sample Post 3', 'This is the third sample post.', '', '3'));

        // $dbh = $di->get('dbh');
        // $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Routes
        // $this->modifyRouter($di);
        $adr->get('ListAllPosts', '/', 'Portfolio\Domain\Interactor\ListAllPosts')
            ->input('Portfolio\Delivery\Input\NoneExpected')
            ->defaults(['_view' => 'listposts.php']);

        $adr->get('DisplaySinglePost', '/{id}/', 'Portfolio\Domain\Interactor\DisplaySinglePost')
            ->input('Portfolio\Delivery\Input\IdOnly')
            ->defaults(['_view' => 'singlepost.php']);

        $adr->post('CreateNewPost', '/', 'Portfolio\Domain\Interactor\CreateNewPost')
            ->input('Portfolio\Delivery\Input\CreateNewPost')
            ->responder('Portfolio\Delivery\Responder\Redirect');

        $adr->get('Mikka', '/mikka', 'Portfolio\Domain\Interactor\ListAllPosts')
            ->input('Portfolio\Delivery\Input\NoneExpected')
            ->responder('Portfolio\Delivery\Responder\AuraViewResponder')
            ->defaults(['_content' => 'content.php']);


        // genneric responder test
        $adr->get('Generic', '/generic/responder', 'Portfolio\Domain\Interactor\ListAllPosts')
            ->input('Portfolio\Delivery\Input\NoneExpected')
            ->responder('Portfolio\Delivery\Responder\GenericResponder')
            ->defaults(['_view' => 'listposts.php']);


        $adr->get('Mikka2', '/mikka2', 'Portfolio\Domain\Interactor\ListAllPosts')
            ->input('Portfolio\Delivery\Input\NoneExpected')
            ->responder('Portfolio\Delivery\Responder\AuraViewResponder')
            ->defaults(['_content' => 'mikka.php']);

        // this saves post variables
        $adr->post('CreateNewPostMikka2', '/mikka2', 'Portfolio\Domain\Interactor\CreateNewPost')
            ->input('Portfolio\Delivery\Input\CreateNewPost')
            ->responder('Portfolio\Delivery\Responder\Redirect');




    }



}
