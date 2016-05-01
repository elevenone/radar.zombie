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

$view->setLayout('default');

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




