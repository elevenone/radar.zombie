<?php
/**
 *
 * bootstrap file brings good luck
 *
 */
use josegonzalez\Dotenv\Loader as Dotenv;
use Radar\Adr\Boot;
use Relay\Middleware\ExceptionHandler;
use Relay\Middleware\ResponseSender;
use Zend\Diactoros\Response as Response;
use Zend\Diactoros\ServerRequestFactory as ServerRequestFactory;
// use Jnjxp\Routeless\Config as RoutlessConfig;


/**
 * Boot
 */
$boot = new Boot();

// boot adr with Config
$adr = $boot->adr([
    Application\Config\Application::class,
	Jnjxp\Routeless\Config::class
    // 'Application\\Config\\Routes',
]);

/**
 * Middleware
 */
// defined in config container

/**
 * Routes
 */
// defined in config container

/**
 * Run
 */
$adr->run(ServerRequestFactory::fromGlobals(), new Response());