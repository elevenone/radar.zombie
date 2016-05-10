<?php

use Relay\RelayBuilder;
use Jnjxp\Viewd\Viewd;
use Aura\View\ViewFactory;
use Aura\Html\HelperLocatorFactory;
use Relay\Middleware\ExceptionHandler;
use Relay\Middleware\ResponseSender;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;




$view = (new ViewFactory)->newInstance(
    (new HelperLocatorFactory)->newInstance()
);


$request = ServerRequestFactory::fromGlobals();
$method = $request->getMethod();
$path = $request->getUri()->getPath();

echo '<pre><hr/>';
print_r( $path );
echo '</pre><hr/>';
/*
$request = ServerRequestFactory::fromGlobals();
$method  = $request->getMethod();
$path    = $request->getUri()->getPath();
$accept  = $request->getHeader('Accept');
$data    = json_decode((string) $request->getBody());
$query   = $request->getQueryParams();
$cookies = $request->getCookieParams();
$files   = $request->getFileParams();
*/
// https://slides.mwop.net/2015-04-10-PSR7-in-the-Middle/#/10/3

$default = 'default';

// if( !isset( $path ) OR empty($path) OR $path === '')
if( !is_null( $path ) )
{
    $layout = 'index';
} else {
    $layout = ltrim($path, '/');
}

$view->setLayout( $layout );




$templates = dirname(__DIR__) . '/application.viewd/templates';

echo "{$templates}/views";
echo '<hr/>';
echo "{$templates}/layouts";
echo '<hr/>';


$view->getViewRegistry()->setPaths(["{$templates}/views"]);
$view->getLayoutRegistry()->setPaths(["{$templates}/layouts"]);

$queue = [
    new ResponseSender(),
    new ExceptionHandler(new Response()),
    new Viewd($view)
];

$relay = (new RelayBuilder)->newInstance($queue);
$relay(ServerRequestFactory::fromGlobals(), new Response());




