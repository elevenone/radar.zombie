<?php
/**
 *
 * // aaa
 *
 */
use josegonzalez\Dotenv\Loader as Dotenv;
use Radar\Adr\Boot;
use Relay\Middleware\ExceptionHandler;
use Relay\Middleware\ResponseSender;
use Zend\Diactoros\Response as Response;
use Zend\Diactoros\ServerRequestFactory as ServerRequestFactory;



/**
 * Boot
 */
$boot = new Boot();
/** @var \Radar\Adr\Adr|\Aura\Router\Map $adr */
$adr = $boot->adr([
	'Application\Config\Application',
	// 'Application\\Config\\Routes',
]);



/**
 * Middleware
 */
$adr->middle(new ResponseSender());
$adr->middle(new ExceptionHandler(new Response()));
$adr->middle('Radar\Adr\Handler\RoutingHandler');
$adr->middle('Radar\Adr\Handler\ActionHandler');

/**
 * Routes
 */
// in config container



/**
 * Run
 */
$adr->run(ServerRequestFactory::fromGlobals(), new Response());