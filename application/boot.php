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
	'Application\\Config\\Routes'
]);

/**
 * Middleware
 */
$adr->middle(new ResponseSender());
$adr->middle(new ExceptionHandler(new Response()));
$adr->middle('Radar\\Adr\\Handler\\RoutingHandler');
$adr->middle('Radar\\Adr\\Handler\\ActionHandler');

/**
 * Routes
 *
 * The Domain specification is a string or an array:
 * * If a string, Radar will instantiate this class using the internal dependency injection container and call its __invoke() method with the user input from the HTTP request.
 * * If an array in the format ['ClassName', 'method'], the dependency injection container will instantiate the specfied class name, and then call the specified method with the user input from the HTTP request.
 *
 */
// $adr->get('site.index', '/{name}', ['Application\\Domain\\Index', 'index']);



/**
 * Run
 */
$adr->run(ServerRequestFactory::fromGlobals(), new Response());